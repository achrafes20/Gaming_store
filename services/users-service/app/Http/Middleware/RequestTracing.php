<?php

namespace App\Http\Middleware;

use App\Support\Tracing;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Starts a span for the request (reusing an inbound `traceparent` if this
 * call came from another service in the mesh) and ships it to Jaeger after
 * the response is sent — see App\Support\Tracing and docs/observability.md.
 * `terminate()` runs after the response has already gone out to the client,
 * so exporting the span here can never add latency to the request itself.
 */
class RequestTracing
{
    public function handle(Request $request, Closure $next): Response
    {
        Tracing::start($request->method().' '.$request->path(), $request->header('traceparent'));

        $response = $next($request);
        $response->headers->set('traceparent', Tracing::outgoingHeader());

        return $response;
    }

    public function terminate(Request $request, Response $response): void
    {
        Tracing::finish($response->getStatusCode());
    }
}
