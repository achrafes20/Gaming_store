<?php

namespace App\Services;

class OrdersClient extends ApiClient
{
    public function __construct()
    {
        parent::__construct(config('services.orders_service_url'));
    }

    public function cart(): array
    {
        return $this->request('GET', '/api/cart');
    }

    public function addToCart(int $productId): array
    {
        return $this->request('POST', '/api/cart', ['form_params' => ['product_id' => $productId]]);
    }

    public function incrementCart(int $cartId): array
    {
        return $this->request('POST', "/api/cart/{$cartId}/increment");
    }

    public function decrementCart(int $cartId): array
    {
        return $this->request('POST', "/api/cart/{$cartId}/decrement");
    }

    public function removeFromCart(int $cartId): array
    {
        return $this->request('DELETE', "/api/cart/{$cartId}");
    }

    public function previewCoupon(string $code, float $total): array
    {
        return $this->request('POST', '/api/coupons/preview', ['form_params' => ['code' => $code, 'total' => $total]]);
    }

    public function checkout(array $data): array
    {
        return $this->request('POST', '/api/orders', ['form_params' => $data]);
    }

    public function orders(): array
    {
        return $this->request('GET', '/api/orders');
    }

    public function allOrders(): array
    {
        return $this->request('GET', '/api/admin/orders');
    }

    public function coupons(): array
    {
        return $this->request('GET', '/api/coupons');
    }

    public function createCoupon(array $data): array
    {
        return $this->request('POST', '/api/coupons', ['form_params' => $data]);
    }

    public function deleteCoupon(int $id): array
    {
        return $this->request('DELETE', "/api/coupons/{$id}");
    }
}
