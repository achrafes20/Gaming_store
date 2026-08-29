<?php

namespace App\Services;

class UsersClient extends ApiClient
{
    public function __construct()
    {
        parent::__construct(config('services.users_service_url'));
    }

    public function register(array $data): array
    {
        return $this->request('POST', '/api/register', ['form_params' => $data]);
    }

    public function login(array $data): array
    {
        return $this->request('POST', '/api/login', ['form_params' => $data]);
    }

    public function favorites(): array
    {
        return $this->request('GET', '/api/favorites');
    }

    public function toggleFavorite(int $productId): array
    {
        return $this->request('POST', "/api/favorites/{$productId}/toggle");
    }

    public function contactReviews(): array
    {
        return $this->request('GET', '/api/reviews');
    }

    public function storeContactReview(array $data): array
    {
        return $this->request('POST', '/api/reviews', ['form_params' => $data]);
    }

    public function deleteContactReview(int $id): array
    {
        return $this->request('DELETE', "/api/reviews/{$id}");
    }

    public function subscribe(string $email): array
    {
        return $this->request('POST', '/api/sub', ['form_params' => ['email' => $email]]);
    }

    public function users(): array
    {
        return $this->request('GET', '/api/users');
    }

    public function promote(int $userId): array
    {
        return $this->request('POST', "/api/users/{$userId}/promote");
    }

    public function demote(int $userId): array
    {
        return $this->request('POST', "/api/users/{$userId}/demote");
    }
}
