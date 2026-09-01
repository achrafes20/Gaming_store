# AI Assistant (Phase 8)

A Gemini-backed chatbot, aware of the caller's role, that answers questions
about the store using real, live data — not a static FAQ. See
[`docs/architecture.md`](architecture.md#chatbot-service) for the
architectural reasoning (why it's a separate service, why it forwards the
caller's own JWT instead of becoming a new trust boundary).

## Getting a Gemini API key

1. Go to <https://aistudio.google.com/apikey> and sign in with a Google
   account.
2. Create an API key (free tier — generous quota, no credit card needed).
3. Paste it into `services/chatbot-service/.env`:
   ```
   GEMINI_API_KEY=your-key-here
   ```
   `scripts/setup-env.sh` creates this file for you with the key left
   blank and a reminder — it's the one secret in this project that can't
   be auto-generated the way `JWT_SECRET`/`INTERNAL_SERVICE_SECRET` are.

Without a key, every other part of the app works exactly as before — the
chat widget just returns a friendly "having trouble" message instead of a
reply (`ChatController` degrades gracefully on any non-200 from
`chatbot-service`, and `GeminiClient` itself throws a clear, logged error
rather than silently failing).

## What it can do

Read-only, by design (Phase 8 scope — see `docs/architecture.md`):

| Tool | Available to | Calls |
|---|---|---|
| `search_products` | everyone | `catalog-service` `GET /api/products` |
| `get_product` | everyone | `catalog-service` `GET /api/products/{id}` |
| `get_my_cart` | any logged-in user | `orders-service` `GET /api/cart` |
| `get_my_orders` | any logged-in user | `orders-service` `GET /api/orders` |
| `get_all_orders` | **admin only** | `orders-service` `GET /api/admin/orders` |

Every tool call forwards the caller's own JWT — `orders-service`'s existing
owner-scoped queries and `jwt.auth:admin` guard are what actually decide
what a given call is allowed to see, exactly as if the user had called
those endpoints themselves. `chatbot-service` adds no privilege of its own;
see `ChatTools::declarationsFor()` and `docs/architecture.md`.

No write actions (nothing adds to a cart, applies a coupon, etc.) — a
deliberate scope decision for this phase, not a technical limitation.

## Model

Defaults to `gemini-3.6-flash` — fast and comfortably within the free
tier's rate limits for a demo. Override via `GEMINI_MODEL` in
`services/chatbot-service/.env` if you'd rather trade latency for a Pro
model's answer quality (Gemini model names/availability move fast — if the
default ever 404s with "no longer available", check
<https://ai.google.dev/gemini-api/docs/models> for the current lineup and
update `GEMINI_MODEL`; `GeminiClient` surfaces the exact API error in
`storage/logs/laravel.log` rather than failing silently, which is how this
was caught in the first place, mid-Phase-8).

**Free tier daily quota**: new Google AI Studio projects get a small daily
request quota per model (as low as 20/day when this was tested) —
comfortably enough for a demo session, but easy to exhaust while actively
developing/testing against it, as this project's own Phase 8 build did. A
`429 RESOURCE_EXHAUSTED` in the logs means that, not a bug; it resets daily,
or a second project/key sidesteps it immediately.

## Trying it

```bash
docker compose up --build
```

Register/log in at <http://localhost:8080>, click the chat bubble
(bottom-right). Try:
- *"What's in my cart?"* / *"Do you have any mice in stock?"* as any user.
- *"How many orders have there been today?"* — works for an `admin`
  account, gets a graceful refusal for a `client` account (try promoting
  yourself first, see the main `README.md`).

## Observability

Same as every other service (Phase 6): `/metrics` (Golden Signals — an
extra one worth watching here is `app_http_request_duration_seconds` for
the `/chat` route, since a multi-tool-call turn can legitimately take a
few seconds), and a chat turn that calls a tool produces one real trace in
Jaeger spanning `chatbot-service -> orders-service` (or `-> catalog-service`),
visible via the `traceparent` propagated by `App\Support\Tracing` the same
way every other cross-service call in this codebase already does.
