<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;

/**
 * users-service is the only service allowed to mint tokens — everyone else
 * only verifies them against the shared JWT_SECRET (see docs/architecture.md).
 */
class JwtIssuer
{
    public function issue(User $user): string
    {
        $now = time();

        return JWT::encode([
            'sub' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'iat' => $now,
            'exp' => $now + (60 * 60 * 24), // 24h
        ], config('services.jwt_secret'), 'HS256');
    }
}
