<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * A minimal, dependency-free distributed tracer: hand-builds OTLP/HTTP JSON
 * spans (the same wire format the full OpenTelemetry SDK would send) and
 * posts them straight to Jaeger's built-in OTLP receiver — no SDK, no
 * ext-grpc, just Laravel's Http facade, matching how every other
 * inter-service call in this codebase is made. Real spans, real trace IDs,
 * genuinely visible in the Jaeger UI — see docs/observability.md for why
 * the full SDK was skipped.
 *
 * Propagation follows the actual W3C Trace Context spec (the `traceparent`
 * header — https://www.w3.org/TR/trace-context/), so this interoperates
 * with any real tracing tool, not just this hand-rolled exporter.
 */
class Tracing
{
    private static ?string $traceId = null;

    private static ?string $spanId = null;

    private static ?string $parentSpanId = null;

    private static float $startedAt = 0.0;

    private static string $name = '';

    /** Start a span for the current request, reusing an inbound trace ID if present (W3C `traceparent`). */
    public static function start(string $name, ?string $traceparent): void
    {
        self::$name = $name;
        self::$startedAt = microtime(true);
        self::$spanId = bin2hex(random_bytes(8));

        if ($traceparent && preg_match('/^00-([0-9a-f]{32})-([0-9a-f]{16})-[0-9a-f]{2}$/', $traceparent, $m)) {
            self::$traceId = $m[1];
            self::$parentSpanId = $m[2];
        } else {
            self::$traceId = bin2hex(random_bytes(16));
            self::$parentSpanId = null;
        }
    }

    /** The `traceparent` value to forward on any outbound call made while handling this request. */
    public static function outgoingHeader(): ?string
    {
        if (! self::$traceId || ! self::$spanId) {
            return null;
        }

        return sprintf('00-%s-%s-01', self::$traceId, self::$spanId);
    }

    public static function traceId(): ?string
    {
        return self::$traceId;
    }

    /** Ship the span to Jaeger. Fire-and-forget: never let tracing slow down or break a real request. */
    public static function finish(int $statusCode): void
    {
        // No configured collector (e.g. JAEGER_OTLP_URL="" in phpunit.xml) —
        // skip entirely rather than attempt a real network call. A DNS
        // lookup for a nonexistent host in a test environment can take
        // longer than the request timeout below is meant to guard against,
        // slowing the whole suite down badly across hundreds of requests —
        // found by a real test run timing out, not by inspection.
        if (! config('services.jaeger_otlp_url') || ! self::$traceId || ! self::$spanId) {
            return;
        }

        $endedAt = microtime(true);
        $service = config('app.name', 'unknown-service');

        $span = [
            'traceId' => self::$traceId,
            'spanId' => self::$spanId,
            'name' => self::$name,
            'kind' => 2, // SPAN_KIND_SERVER
            'startTimeUnixNano' => (string) (int) (self::$startedAt * 1e9),
            'endTimeUnixNano' => (string) (int) ($endedAt * 1e9),
            'attributes' => [
                ['key' => 'http.status_code', 'value' => ['intValue' => $statusCode]],
            ],
        ];
        if (self::$parentSpanId) {
            $span['parentSpanId'] = self::$parentSpanId;
        }

        $payload = [
            'resourceSpans' => [[
                'resource' => [
                    'attributes' => [
                        ['key' => 'service.name', 'value' => ['stringValue' => $service]],
                    ],
                ],
                'scopeSpans' => [[
                    'scope' => ['name' => 'gaming-store'],
                    'spans' => [$span],
                ]],
            ]],
        ];

        try {
            Http::baseUrl(config('services.jaeger_otlp_url'))
                ->timeout(1)
                ->post('/v1/traces', $payload);
        } catch (\Throwable $e) {
            // Tracing is best-effort observability, never a reason to fail a request.
            Log::debug('Failed to export trace span to Jaeger', ['error' => $e->getMessage()]);
        }
    }
}
