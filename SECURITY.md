# Security

## Scope of this document

This covers the automated scanning wired into CI (`.github/workflows/ci.yml`)
and how findings are triaged, the application-security controls added in
Phase 5 (security headers, rate limiting, JWT validation, internal-endpoint
authentication), and how they map to the OWASP Top 10. See `plan.md` for the
full phase-by-phase build log and verification evidence.

## What's scanned, and how failures are handled

| Check | Tool | On failure |
|---|---|---|
| Known CVEs in Composer dependencies | `composer audit` | **Blocks the pipeline** |
| Known CVEs in npm dependencies | `npm audit --audit-level=high` | Reported, does not block yet (`continue-on-error: true`) — no service currently ships JS beyond the default Laravel/Vite scaffold, so this is low-stakes for now; will start blocking once `web-bff`'s frontend surface grows |
| Committed secrets | [gitleaks](https://github.com/gitleaks/gitleaks) | **Blocks the pipeline** |
| Common PHP/injection/OWASP patterns in source | [Semgrep](https://semgrep.dev) (`p/php p/owasp-top-ten p/secrets p/security-audit`) | **Blocks the pipeline** |
| Known CVEs in the built container image (OS packages + PHP deps) | [Trivy](https://aquasecurity.github.io/trivy/) | Reported, does not block yet (`exit-code: '0'`) — base images (`php:8.2-fpm-alpine`, `mysql:8.0`, `rabbitmq:3-management-alpine`) carry a background rate of upstream CVEs that isn't realistic to keep at zero for a portfolio project; reviewed manually instead of gating every push on it |

The two `continue-on-error`/report-only exceptions are a deliberate choice for
this project's stage, not an oversight — flip them to blocking in
`ci.yml` once there's a real triage process (an issue tracker label, a
person responsible for reviewing findings) to receive them.

## Handling of payment and account data

- Card numbers are never stored in full — only the last 4 digits
  (`orders-service`, `Payments` model). The CVV is validated at checkout but
  never persisted anywhere, per PCI-DSS guidance for merchants who don't
  need to store it.
- Passwords are hashed with bcrypt (Laravel's default `Hash::make`), never
  logged or returned in API responses.
- JWTs are signed with a secret shared only between `catalog-service`,
  `orders-service`, and `users-service` (`JWT_SECRET`) — never committed;
  see `scripts/setup-env.sh` / `scripts/k8s-secrets.sh`.

## Application-security controls (Phase 5)

### Security headers

Every service (`catalog-service`, `orders-service`, `users-service`,
`web-bff`) appends a `SecurityHeaders` middleware globally
(`app/Http/Middleware/SecurityHeaders.php`, registered in
`bootstrap/app.php`): `X-Content-Type-Options: nosniff`,
`X-Frame-Options: DENY`, `Referrer-Policy: strict-origin-when-cross-origin`,
a restrictive `Permissions-Policy`, and a `Content-Security-Policy`.

The 3 JSON-only API services use a maximally strict CSP
(`default-src 'none'; frame-ancestors 'none'` — they never render HTML, so
nothing needs to be allowed). `web-bff` renders real HTML (the cyberpunk
theme carried over from the monolith in Phase 1) that relies on inline
`<script>`/`onclick=`/`style=""` attributes throughout its Blade views — a
strict CSP there would break the working site, so its policy keeps
`'unsafe-inline'` on `script-src`/`style-src` as a **documented, deliberate
trade-off**, not an oversight. Tightening it to a nonce-based CSP would mean
touching every view carrying an inline handler — out of scope for this
pass, listed here as a known limitation. Everything else (`object-src`,
`base-uri`, `frame-ancestors`) is locked down as normal.

Verified for real: `curl -sI` against both a JSON endpoint and the rendered
homepage through the gateway shows all headers present, and the homepage
still renders correctly (CSS/logo/images) with the relaxed CSP in place —
not just "the header is set", the page actually works with it.

### Rate limiting

Named `RateLimiter` limiters (`AppServiceProvider::boot()`), applied via
`throttle:<name>` on the routes listed in the plan:

| Limiter | Rate | Applied to |
|---|---|---|
| `auth` | 5/min per IP | `users-service` `/api/login`, `/api/register`; `web-bff` `/login`, `/register` |
| `checkout` | 10/min per IP | `orders-service` `/api/orders` (POST); `web-bff` `/StoreOrder` |

Verified for real against the running stack (not just unit tests): 7 rapid
login attempts through the gateway returned the expected sequence — 5
processed, then `429 Too Many Requests` on the 6th and 7th.

**Bug found and fixed while testing this against real containers** (invisible
in `php artisan test`, since `phpunit.xml` already sets `CACHE_STORE=array`
for every service): `web-bff`'s `.env`/`k8s` ConfigMap had `CACHE_STORE=database`
copied from the other services, but `web-bff` has no real database of its
own (`DB_CONNECTION=sqlite` there is unused/unmigrated — the BFF is
stateless by design). The rate limiter's first read from the `database`
cache store threw a SQL error querying a `cache` table that doesn't exist,
turning every throttled route into a 500. Fixed by switching `web-bff` to
`CACHE_STORE=file` (consistent with its existing `SESSION_DRIVER=file`
pattern) in `.env.example` and `k8s/base/web-bff/configmap.yaml`. Re-verified
after the fix: a full register → session → cart → checkout-page journey
through the gateway works end to end.

### Centralized JWT validation

`catalog-service`, `orders-service`, and `users-service` each carry an
identical `JwtAuth` middleware (`app/Http/Middleware/JwtAuth.php`) — verifies
the signature/expiry of the token issued by `users-service` against the
shared `JWT_SECRET`, entirely locally (no network call between services just
to authenticate a request). This predates Phase 5 (built in Phase 0) but is
recorded here as satisfying that plan item: one canonical implementation,
duplicated identically across the 3 services rather than diverging copies —
a shared internal package would remove the duplication but was judged
overkill for 3 near-identical files at this project's scale.

### Internal-endpoint authentication (bug found and fixed in this phase)

`catalog-service`'s `/api/internal/products/{id}/decrement-stock` and
`orders-service`'s `/api/internal/has-purchased` are service-to-service-only
endpoints (called directly, container-to-container, never through the
gateway — see `docs/architecture.md`). **Auditing them for this phase found
they had no authentication at all** — reachable by anyone through the public
gateway (`GET /api/orders/internal/has-purchased?user_id=1&product_id=1`
would leak purchase history for any user/product pair; the decrement-stock
endpoint could be hit directly to corrupt stock counts). OWASP Top 10:
**A01:2021 – Broken Access Control**.

Fixed with defense in depth:
1. **Gateway-level block** (primary): `gateway/nginx.conf` and
   `k8s/base/api-gateway/configmap.yaml` both return `403` on
   `/api/catalog/internal/` and `/api/orders/internal/` before the request
   ever reaches a service.
2. **Application-level shared secret** (fallback, for a request that reaches
   the service directly — a port-forward, a future `NetworkPolicy` gap): a
   new `VerifyInternalSecret` middleware checks an `X-Internal-Secret` header
   against `INTERNAL_SERVICE_SECRET`, a secret shared only between
   `catalog-service` and `orders-service` (generated by
   `scripts/setup-env.sh` / `scripts/k8s-secrets.sh`, alongside `JWT_SECRET`
   — never committed).

Verified for real: through the gateway, both internal paths now return `403`
(confirmed via `curl`); hitting a service directly without the header (or
with the wrong one) also returns `403` at the application layer; with the
correct header, the legitimate service-to-service calls (checkout →
decrement stock, review submission → has-purchased) still work — the full
checkout flow was re-run end to end and still decrements stock correctly.
New tests added (`test_internal_endpoint_rejects_requests_without_the_shared_secret`
in both services) assert the 403 directly, not just exercised manually.

## `chatbot-service` — no new trust boundary (Phase 8)

The AI assistant (`docs/chatbot.md`) never gets its own elevated
credential. Every tool it can call forwards the *caller's own JWT* to a
real, already-authorized endpoint (`orders-service`'s owner-scoped
`/api/cart`/`/api/orders`, its `jwt.auth:admin`-guarded
`/api/admin/orders`) — so a prompt-injection attempt to "call the admin
tool anyway" gets exactly as far as that same JWT would get calling that
route directly: a `403` from `orders-service` itself, not a check that
lives in (and could be talked out of) the prompt. `GEMINI_API_KEY` is
generated manually (Google AI Studio, free tier) and injected the same way
`INTERNAL_SERVICE_SECRET` was in Phase 5 — never committed. `/chat` is
rate-limited (both on `chatbot-service` and on `web-bff`'s proxying route)
to bound abuse and API cost. See `docs/architecture.md`'s `chatbot-service`
section for the full reasoning.

## OWASP Top 10 (2021) mapping

| # | Category | Status | Where |
|---|---|---|---|
| A01 | Broken Access Control | Addressed | JWT role checks (`jwt.auth:admin`) on every admin route; cart ownership check (Phase 3 IDOR fix); internal-endpoint secret + gateway block (this phase) |
| A02 | Cryptographic Failures | Addressed | Passwords bcrypt-hashed; JWTs HS256-signed with a 32-byte secret; card numbers never stored beyond the last 4 digits, CVV never persisted |
| A03 | Injection | Addressed | Eloquent/query builder everywhere (no raw SQL string concatenation); Semgrep's `p/owasp-top-ten` pack scans every push |
| A04 | Insecure Design | Partially addressed | Database-per-service, JWT stateless auth, internal-only endpoints now actually enforced as internal-only (this phase) — no formal threat-modeling pass performed |
| A05 | Security Misconfiguration | Addressed | Security headers on all 5 services (this phase); secrets never committed (`jwt-secret`, `INTERNAL_SERVICE_SECRET`, `APP_KEY`, SMTP generated by script); `APP_DEBUG` should be set to `false` for any real deployment beyond this demo (currently `true` in K8s ConfigMaps for local debugging convenience — flagged here as a known gap, not hidden) |
| A06 | Vulnerable and Outdated Components | Addressed | `composer audit` (blocking), `npm audit` (report-only), Trivy image scan (report-only) — see table above |
| A07 | Identification and Authentication Failures | Addressed | Centralized JWT validation (identical middleware, 3 services); rate limiting on `/login`/`/register` (this phase); expired-token rejection covered by tests |
| A08 | Software and Data Integrity Failures | Addressed | GitOps (Phase 4): git is the single source of truth for what's deployed, Argo CD `selfHeal` reverts out-of-band changes; CI builds images from a pinned `composer.lock`/`package-lock.json` |
| A09 | Security Logging and Monitoring Failures | Not addressed | No centralized log aggregation or alerting yet — tracked as Phase 6 (Monitoring & Observability) |
| A10 | Server-Side Request Forgery | Addressed | The only server-initiated outbound requests are to hardcoded, config-defined internal service URLs (`CATALOG_SERVICE_URL`, `ORDERS_SERVICE_URL`) — no user-controlled URL is ever fetched server-side |

## Reporting

This is a portfolio/demo project — there is no live bug bounty. If you spot
something while reviewing the code, opening an issue is fine.
