<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards the /api/internal/* routes — service-to-service only, called
 * directly by another microservice (never through a human browser or the
 * public gateway, see docs/architecture.md). Found with NO authentication
 * at all before this middleware existed: reachable by anyone through the
 * gateway (/api/catalog/internal/...) — see SECURITY.md, OWASP A01.
 *
 * The gateway itself now also blocks these paths (see gateway/nginx.conf)
 * as the primary defense; this middleware is the second layer, for a
 * request that reaches the service directly (a port-forward, a future
 * NetworkPolicy gap) rather than through the gateway.
 */
class VerifyInternalSecret
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('services.internal_service_secret');
        $given = (string) $request->header('X-Internal-Secret', '');

        if ($expected === '' || ! hash_equals($expected, $given)) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return $next($request);
    }
}
