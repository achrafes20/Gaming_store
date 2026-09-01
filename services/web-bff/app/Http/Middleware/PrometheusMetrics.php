<?php

namespace App\Http\Middleware;

use App\Support\Metrics;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * Records the Golden Signals for every request: traffic (a counter of every
 * request), latency (a histogram of request duration), and errors (a
 * counter of 5xx responses). Saturation is sampled here too, as a gauge —
 * open DB connections is the cheapest available proxy for it without adding
 * extra infrastructure (a real APM would also track CPU/memory, out of
 * scope for this app-level middleware). See SECURITY.md/docs/observability.md
 * for why /metrics itself needs no auth (unreachable from outside the
 * Docker/K8s network — see gateway/nginx.conf).
 */
class PrometheusMetrics
{
    public function handle(Request $request, Closure $next): Response
    {
        // Don't let scraping /metrics itself pollute the very metrics it reports.
        if ($request->path() === 'metrics') {
            return $next($request);
        }

        $start = microtime(true);
        $response = $next($request);
        $duration = microtime(true) - $start;

        $route = optional($request->route())->uri() ?? 'unmatched';
        $method = $request->method();
        $status = (string) $response->getStatusCode();
        $registry = Metrics::registry();

        $registry->getOrRegisterCounter('app', 'http_requests_total', 'Total HTTP requests (traffic)', ['method', 'route', 'status'])
            ->inc([$method, $route, $status]);

        $registry->getOrRegisterHistogram('app', 'http_request_duration_seconds', 'HTTP request duration in seconds (latency)', ['method', 'route'], [0.01, 0.05, 0.1, 0.25, 0.5, 1, 2.5, 5])
            ->observe($duration, [$method, $route]);

        if ($response->getStatusCode() >= 500) {
            $registry->getOrRegisterCounter('app', 'http_errors_total', 'Total 5xx responses (errors)', ['method', 'route'])
                ->inc([$method, $route]);
        }

        try {
            $registry->getOrRegisterGauge('app', 'db_connections_open', 'Open DB connections (saturation)')
                ->set(count(DB::getConnections()));
        } catch (\Throwable) {
            // No DB configured for this service (or not connected yet) — saturation gauge just stays unset.
        }

        return $response;
    }
}
