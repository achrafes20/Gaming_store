<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Baseline security headers — see SECURITY.md.
 *
 * Unlike the API services, this one renders real HTML (the cyberpunk theme
 * carried over from the monolith, Phase 1), which relies on inline
 * <script>/onclick= and style="" attributes throughout the Blade views —
 * a strict CSP would break the working site. `unsafe-inline` on
 * script-src/style-src is a known, deliberate trade-off rather than an
 * oversight (documented in SECURITY.md): tightening it to a nonce-based CSP
 * would mean touching every view that carries an inline handler, which is
 * out of scope for this pass. Everything else is locked down as normal.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
        $response->headers->set('Content-Security-Policy', implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline'",
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data:",
            "font-src 'self' data:",
            "object-src 'none'",
            "base-uri 'self'",
            "frame-ancestors 'none'",
        ]));

        return $response;
    }
}
