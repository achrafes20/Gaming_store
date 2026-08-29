# Security

## Scope of this document

This covers the automated scanning already wired into CI
(`.github/workflows/ci.yml`) and how findings are triaged today. A full
application-security pass (security headers, rate limiting, a proper OWASP
Top 10 checklist) is tracked separately — see `plan.md`, Phase 5.

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

## Reporting

This is a portfolio/demo project — there is no live bug bounty. If you spot
something while reviewing the code, opening an issue is fine.
