<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use UnexpectedValueException;

/**
 * Verifies the JWT issued by users-service, without any network call
 * (services trust the shared JWT_SECRET, see docs/architecture).
 */
class JwtAuth
{
    public function handle(Request $request, Closure $next, ?string $requiredRole = null): Response
    {
        $header = $request->header('Authorization', '');

        if (! str_starts_with($header, 'Bearer ')) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $token = substr($header, 7);

        try {
            $claims = JWT::decode($token, new Key(config('services.jwt_secret'), 'HS256'));
        } catch (ExpiredException) {
            return response()->json(['message' => 'Token expired.'], 401);
        } catch (UnexpectedValueException) {
            return response()->json(['message' => 'Invalid token.'], 401);
        }

        if ($requiredRole && ($claims->role ?? null) !== $requiredRole) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $request->attributes->set('auth_user', [
            'id' => $claims->sub,
            'name' => $claims->name ?? null,
            'email' => $claims->email ?? null,
            'role' => $claims->role ?? 'client',
        ]);

        return $next($request);
    }
}
