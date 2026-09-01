<?php

namespace App\Services;

use App\Support\Tracing;
use Illuminate\Support\Facades\Http;

/**
 * The tools Gemini is allowed to call. Every one of them is a thin
 * pass-through to a real, already-authorized endpoint on another service —
 * this class adds no privilege of its own. Read-only by design (Phase 8
 * scope: Q&A + live-data lookups, never actions/writes) — see
 * docs/architecture.md and docs/chatbot.md for the full reasoning.
 *
 * Every call forwards the CALLER's own JWT (never a service-level secret),
 * so `orders-service`'s existing owner-scoped queries and `jwt.auth:admin`
 * guard are what actually enforce access — not a new trust boundary here.
 * declarationsFor() additionally hides admin tools from a non-admin caller
 * so the model doesn't even try, but that's a UX nicety on top of the real
 * enforcement, not a substitute for it: execute() applies no role check of
 * its own, on purpose, because the downstream service already will.
 */
class ChatTools
{
    /** @return array<int, array> Gemini function declarations, filtered by role. */
    public function declarationsFor(string $role): array
    {
        $tools = [
            [
                'name' => 'search_products',
                'description' => 'Search the store catalog by name and/or category. Returns matching products with price, stock quantity, and description.',
                'parameters' => [
                    'type' => 'OBJECT',
                    'properties' => [
                        'query' => ['type' => 'STRING', 'description' => 'Free-text search on the product name'],
                        'category_id' => ['type' => 'INTEGER', 'description' => 'Filter to one category id'],
                    ],
                ],
            ],
            [
                'name' => 'get_product',
                'description' => 'Get full details for one product by id, including its reviews.',
                'parameters' => [
                    'type' => 'OBJECT',
                    'properties' => ['product_id' => ['type' => 'INTEGER']],
                    'required' => ['product_id'],
                ],
            ],
            [
                'name' => 'get_my_cart',
                'description' => "Get the current user's own shopping cart contents.",
                'parameters' => ['type' => 'OBJECT', 'properties' => new \stdClass],
            ],
            [
                'name' => 'get_my_orders',
                'description' => "Get the current user's own order history.",
                'parameters' => ['type' => 'OBJECT', 'properties' => new \stdClass],
            ],
        ];

        if ($role === 'admin') {
            $tools[] = [
                'name' => 'get_all_orders',
                'description' => 'Admin only: list every order placed by every customer across the whole store.',
                'parameters' => ['type' => 'OBJECT', 'properties' => new \stdClass],
            ];
        }

        return $tools;
    }

    /**
     * @return array the tool's result, always JSON-safe — never throws.
     *
     * Always wrapped as {"result": ...} (or {"error": ...}), never a bare
     * list: Gemini's functionResponse.response field is a proto Struct
     * (a JSON *object*) — several of these calls proxy an endpoint that
     * returns a raw JSON array (the cart/orders list endpoints), which
     * Gemini rejects outright ("Proto field is not repeating, cannot start
     * list") if handed back unwrapped. Found with a real API key, not by
     * reading the spec — the first attempt without this wrapper failed on
     * literally the first tool call tried.
     */
    public function execute(string $name, array $args, string $callerJwt): array
    {
        return ['result' => match ($name) {
            'search_products' => $this->get(config('services.catalog_service_url'), '/api/products', array_filter([
                'q' => $args['query'] ?? null,
                'category_id' => $args['category_id'] ?? null,
            ])),
            'get_product' => $this->get(config('services.catalog_service_url'), '/api/products/'.($args['product_id'] ?? 0)),
            'get_my_cart' => $this->get(config('services.orders_service_url'), '/api/cart', [], $callerJwt),
            'get_my_orders' => $this->get(config('services.orders_service_url'), '/api/orders', [], $callerJwt),
            'get_all_orders' => $this->get(config('services.orders_service_url'), '/api/admin/orders', [], $callerJwt),
            default => ['error' => "Unknown tool: {$name}"],
        }];
    }

    private function get(string $baseUrl, string $path, array $query = [], ?string $jwt = null): array
    {
        $request = Http::baseUrl($baseUrl)->timeout(5);
        if ($jwt) {
            $request = $request->withToken($jwt);
        }
        // Propagates the current request's W3C traceparent (Phase 6) — see
        // App\Support\Tracing — so a chat turn that calls a tool shows up
        // as one trace spanning chatbot-service and whichever service the
        // tool hit, in Jaeger.
        if ($header = Tracing::outgoingHeader()) {
            $request = $request->withHeaders(['traceparent' => $header]);
        }

        try {
            $response = $request->get($path, $query);
        } catch (\Throwable $e) {
            return ['error' => 'That service is temporarily unavailable.'];
        }

        if ($response->status() === 403) {
            return ['error' => 'Not authorized for this — this account does not have admin access.'];
        }

        if ($response->failed()) {
            return ['error' => "Request failed (status {$response->status()})."];
        }

        return $response->json() ?? [];
    }
}
