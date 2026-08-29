<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;

/**
 * Talks to catalog-service — orders-service never reads product data
 * from a local table, it is not the source of truth for it.
 */
class CatalogClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.catalog_service_url'),
            'timeout' => 5,
        ]);
    }

    /** @return array{id:int,name:string,price:float,quantity:int}|null */
    public function findProduct(int $productId): ?array
    {
        try {
            $response = $this->client->get("/api/products/{$productId}");

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException) {
            return null;
        }
    }

    public function decrementStock(int $productId, int $quantity): void
    {
        try {
            $response = $this->client->patch("/api/internal/products/{$productId}/decrement-stock", [
                'form_params' => ['quantity' => $quantity],
            ]);
        } catch (GuzzleException $e) {
            throw new RuntimeException("catalog-service unreachable while decrementing stock for product {$productId}: {$e->getMessage()}");
        }

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException("catalog-service refused to decrement stock for product {$productId} (insufficient stock).");
        }
    }
}
