<?php

namespace Tests\Concerns;

use Firebase\JWT\JWT;

/**
 * Mints a JWT the same way users-service would, signed with the shared
 * JWT_SECRET (set in phpunit.xml) — lets tests exercise JwtAuth-protected
 * routes without a real users-service running.
 */
trait ActsWithJwt
{
    protected function jwtHeaders(int $userId = 1, string $role = 'client', array $overrides = []): array
    {
        $now = time();

        $claims = array_merge([
            'sub' => $userId,
            'name' => 'Test User',
            'email' => "user{$userId}@example.com",
            'role' => $role,
            'iat' => $now,
            'exp' => $now + 3600,
        ], $overrides);

        $token = JWT::encode($claims, config('services.jwt_secret'), 'HS256');

        return ['Authorization' => "Bearer {$token}"];
    }

    protected function adminHeaders(int $userId = 1): array
    {
        return $this->jwtHeaders($userId, 'admin');
    }
}
