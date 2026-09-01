# Observability (Phase 6)

Metrics, logs, and traces for the whole stack — this is where going
microservices actually pays off for observability: a single request can now
span 3-4 processes, and a slow checkout could be a slow DB query in
`catalog-service`, a slow RabbitMQ publish in `orders-service`, or a slow
SMTP call in `notifications-service`. None of that distinction exists in a
monolith; here it's the whole point of this phase.

## Stack

| Concern | Tool | Reached at (docker-compose) |
|---|---|---|
| Metrics | Prometheus + Grafana | `http://localhost:9090` (Prometheus), `http://localhost:3000` (Grafana, admin/admin) |
| Traces | Jaeger | `http://localhost:16686` |
| Logs | Loki + Promtail, viewed in Grafana | Grafana's Explore tab, datasource "Loki" |

Everything is provisioned automatically (`monitoring/`, mounted read-only
into each container) — nothing to click through by hand on first boot.

## Metrics — the Golden Signals

Each HTTP service (`catalog-service`, `orders-service`, `users-service`,
`web-bff`) exposes `GET /metrics` (`App\Support\Metrics`,
`App\Http\Middleware\PrometheusMetrics`) via
[`promphp/prometheus_client_php`](https://github.com/PromPHP/prometheus_client_php),
backed by APCu (see each Dockerfile) so every php-fpm worker in a container
shares the same counters — a scrape can land on any worker and still see
what every other worker recorded.

- **Traffic**: `http_requests_total{method,route,status}` (counter)
- **Errors**: `http_errors_total{method,route}` (counter, 5xx only)
- **Latency**: `http_request_duration_seconds{method,route}` (histogram)
- **Saturation**: `db_connections_open` (gauge, sampled per request — the
  cheapest available proxy without adding a node-exporter-style agent; a
  real deployment would also track CPU/memory)

`/metrics` is **never reachable from outside** — the gateway returns `403`
on it for `web-bff` (the only one of the 4 sitting behind the gateway's
catch-all `/`; the other 3 simply have no gateway route to `/metrics` at
all, see `gateway/nginx.conf`). Prometheus scrapes it directly,
container-to-container, the same way any other internal call in this
codebase works.

**`notifications-service` is different**: it's a long-running CLI consumer
(`php artisan events:consume`), not an HTTP server, so there's no
`/metrics` endpoint to scrape. It uses the standard Prometheus pattern for
batch/worker jobs instead — **push** its metrics to a Pushgateway
(`services/notifications-service/app/Support/Metrics.php`) after every
message processed, and Prometheus scrapes the Pushgateway like any other
target. Same Golden Signals, different transport:
`events_processed_total{event,status}` and
`event_processing_duration_seconds{event}`.

**RabbitMQ** exposes its own Prometheus metrics via the `rabbitmq_prometheus`
plugin (`docker/rabbitmq/enabled_plugins`), scraped on port `15692`.

### Grafana dashboard

One dashboard, `Gaming Store — Golden Signals`
(`monitoring/grafana/dashboards/gaming-store-golden-signals.json`),
auto-provisioned on first boot. **Deliberately one file with a `$service`
template variable, not four near-identical per-service JSON files** — the
plan's original phrasing suggested "a global dashboard + a per-service
dashboard"; a template variable gets the same outcome (pick any service,
see its own latency/saturation panels) with one file to maintain instead of
four that would drift out of sync with each other. The traffic/errors/
RabbitMQ/notifications panels are unfiltered (the "global" view, all
services overlaid on one graph); latency, saturation, and the
requests-by-status breakdown are filtered by the `$service` variable (the
"per-service" view).

## Tracing — real spans, no SDK

Every service carries `App\Support\Tracing`: it hand-builds OTLP/HTTP JSON
spans — the same wire format the full OpenTelemetry SDK sends — and POSTs
them straight to Jaeger's built-in OTLP receiver via Laravel's `Http`
facade. **Real spans, a real trace ID, genuinely visible in the Jaeger UI**,
without pulling in the OTel SDK (which needs `ext-grpc` or a much heavier
dependency tree than anything else in this codebase uses). Propagation
follows the actual [W3C Trace Context spec](https://www.w3.org/TR/trace-context/)
(the `traceparent` header), so this interoperates with any real tracing
tool, not just this hand-rolled exporter — it was a deliberate, scoped
trade-off (documented here, not hidden) given this project's size, not a
shortcut that breaks compatibility.

Propagation path, end to end:
1. `App\Http\Middleware\RequestTracing` starts a span on every inbound
   request, reusing an inbound `traceparent` if the call came from another
   service in the mesh, or starting a new trace if it's the entry point
   (i.e. a browser hitting `web-bff` through the gateway).
2. Every outbound HTTP call — `web-bff`'s 3 backend clients
   (`app/Services/ApiClient.php`, the one choke point all of them go
   through), `orders-service`'s `CatalogClient`, `catalog-service`'s
   purchase-verification call to `orders-service` — forwards the current
   span's `traceparent` to the next service.
3. **The async boundary is bridged too**: `EventPublisher` (orders-service,
   users-service) embeds the `traceparent` in the RabbitMQ message body
   itself (there's no HTTP header to carry it over AMQP), and
   `notifications-service`'s consumer reads it back out and starts a child
   span from it. A `checkout -> publish order.created -> consume -> send
   email` flow shows up as **one trace** spanning 3 services and an async
   hop, not four disconnected ones — this is the concrete reason
   distributed tracing is worth having now, and would have been meaningless
   in the original monolith.
4. `App\Http\Middleware\RequestTracing::terminate()` exports the span
   *after* the response has already gone to the client — tracing can never
   add latency to a real request. Every export is wrapped in a try/catch
   with a 1s timeout: Jaeger being down degrades to "no trace for this
   request", never a broken request.

## Logs — structured JSON, correlated by trace ID

Every service writes structured JSON logs (`Monolog\Formatter\JsonFormatter`,
`config/logging.php`), and `App\Logging\AddTraceId` (a Monolog processor)
stamps every line from the 4 HTTP services with the current request's trace
ID. A Loki query for one trace ID — or just `grep`-ing `docker compose
logs` for it — surfaces the matching lines across every service that
touched that request.

**Getting the JSON out of the container was not as simple as
`LOG_CHANNEL=stderr`, and that's worth recording**: the first attempt set
`LOG_CHANNEL=stderr` (Monolog writing straight to `php://stderr`) to
replace the previous default (`single`, a file *inside* the container,
invisible to `docker logs`/`kubectl logs` and lost on every restart). It
looked right and cost nothing to try — except it silently logged nothing at
all. Confirmed with a deliberate `Log::error('test')` behind a real HTTP
request: no output anywhere, not even an error about the failure. The
cause: these 4 services run php-fpm behind nginx under supervisord (one
container = one service, see Phase 1), and php-fpm's `catch_workers_output`
setting — meant to forward exactly this kind of worker-process stderr write
up to the master process's own stdout — doesn't reliably do so in this
image. nginx's *own* access log showed up fine the whole time (nginx logs
via its own configured destination, nothing to do with php-fpm workers),
which is what made the app logs' silence easy to miss at first — the
container looked like it was logging normally.

The fix is the standard, well-known workaround for exactly this php-fpm
limitation: log to `storage/logs/laravel.log` as before (the `single`
channel, now with a JSON formatter), and let a `tail -F` process — a new
`program:log-forwarder` in `docker/supervisord.conf` — stream that file to
the container's real stdout, which `docker logs`/`kubectl logs`/Promtail do
reliably capture (a plain file write always succeeds; a `tail -F` on a
process supervisord itself manages sidesteps the whole worker-output
question entirely). `notifications-service` needed none of this: it's a
plain CLI process (`php artisan events:consume`), not a php-fpm worker
under supervisord, so its `LOG_CHANNEL=stderr` writes straight to the
container's actual stdout without an intermediary — confirmed working
as-is.

Promtail (`monitoring/promtail/promtail-config.yml`) discovers every
container via the Docker Engine API (`/var/run/docker.sock`, read-only) and
ships their stdout to Loki. Query them in Grafana's Explore tab, datasource
"Loki", e.g. `{service="catalog-service"} | json`.

## Kubernetes

The Phase 6 stack above is docker-compose only (the plan's own phrasing
scoped Loki that way explicitly; Prometheus/Grafana/Jaeger were left
open-ended). Given the added complexity already carried by this project's
K8s manifests (4 phases' worth of Deployments/StatefulSets/Secrets/Argo CD),
porting the observability stack there — 6 more components, PVCs, and
scrape-target service discovery via K8s `Endpoints` instead of static
compose service names — is scoped out of this pass and left as a natural
next step, the same kind of documented deviation as Phase 2's kind ->
Docker Desktop Kubernetes swap. The application code itself has no
docker-compose-only assumptions baked in: every service still exposes
`/metrics`, still exports spans to whatever `JAEGER_OTLP_URL` points at,
and still logs structured JSON to stdout — so a K8s-native Prometheus
(via `kube-prometheus-stack`, say) would scrape these services with zero
application changes, only new cluster-side manifests.
