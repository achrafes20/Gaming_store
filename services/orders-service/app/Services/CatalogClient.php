<?php

namespace App\Services;

use App\Support\Tracing;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Talks to catalog-service — orders-service never reads product data
 * from a local table, it is not the source of truth for it.
 */
class CatalogClient
{
    /** @return array{id:int,name:string,price:float,quantity:int}|null */
    public function findProduct(int $productId): ?array
    {
        try {
            $response = Http::baseUrl(config('services.catalog_service_url'))
                ->timeout(5)
                ->withHeaders($this->traceHeaders())
                ->get("/api/products/{$productId}");

            return $response->successful() ? $response->json() : null;
        } catch (\Throwable) {
            return null;
        }
    }

    public function decrementStock(int $productId, int $quantity): void
    {
        try {
            $response = Http::baseUrl(config('services.catalog_service_url'))
                ->timeout(5)
                ->withHeaders(array_merge(
                    ['X-Internal-Secret' => config('services.internal_service_secret')],
                    $this->traceHeaders(),
                ))
                ->patch("/api/internal/products/{$productId}/decrement-stock", ['quantity' => $quantity]);
        } catch (\Throwable $e) {
            throw new RuntimeException("catalog-service unreachable while decrementing stock for product {$productId}: {$e->getMessage()}");
        }

        if ($response->status() !== 200) {
            throw new RuntimeException("catalog-service refused to decrement stock for product {$productId} (insufficient stock).");
        }
    }

    /** Propagates the current request's W3C traceparent (Phase 6) — see App\Support\Tracing. */
    private function traceHeaders(): array
    {
        return ($header = Tracing::outgoingHeader()) ? ['traceparent' => $header] : [];
    }
}
