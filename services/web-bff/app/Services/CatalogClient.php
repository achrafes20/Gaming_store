<?php

namespace App\Services;

class CatalogClient extends ApiClient
{
    public function __construct()
    {
        parent::__construct(config('services.catalog_service_url'));
    }

    public function products(array $query = []): array
    {
        return $this->request('GET', '/api/products?'.http_build_query($query));
    }

    public function product(int $id): array
    {
        return $this->request('GET', "/api/products/{$id}");
    }

    public function categories(): array
    {
        return $this->request('GET', '/api/categories');
    }

    public function category(int $id): array
    {
        return $this->request('GET', "/api/categories/{$id}");
    }

    public function reviews(int $productId): array
    {
        return $this->request('GET', "/api/products/{$productId}/reviews");
    }

    public function storeReview(int $productId, array $data): array
    {
        return $this->request('POST', "/api/products/{$productId}/reviews", ['form_params' => $data]);
    }

    public function createProduct(array $data, array $files = []): array
    {
        return $this->request('POST', '/api/products', ['multipart' => $this->multipart($data, $files)]);
    }

    public function updateProduct(int $id, array $data, array $files = []): array
    {
        $data['_method'] = 'PUT';

        return $this->request('POST', "/api/products/{$id}", ['multipart' => $this->multipart($data, $files)]);
    }

    public function deleteProduct(int $id): array
    {
        return $this->request('DELETE', "/api/products/{$id}");
    }

    public function createCategory(array $data, array $files = []): array
    {
        return $this->request('POST', '/api/categories', ['multipart' => $this->multipart($data, $files)]);
    }

    public function updateCategory(int $id, array $data, array $files = []): array
    {
        $data['_method'] = 'PUT';

        return $this->request('POST', "/api/categories/{$id}", ['multipart' => $this->multipart($data, $files)]);
    }

    public function deleteCategory(int $id): array
    {
        return $this->request('DELETE', "/api/categories/{$id}");
    }

    private function multipart(array $data, array $files): array
    {
        $multipart = [];

        foreach ($data as $name => $contents) {
            if ($contents !== null) {
                $multipart[] = ['name' => $name, 'contents' => (string) $contents];
            }
        }

        foreach ($files as $name => $file) {
            if ($file) {
                $multipart[] = ['name' => $name, 'contents' => fopen($file->getRealPath(), 'r'), 'filename' => $file->getClientOriginalName()];
            }
        }

        return $multipart;
    }
}
