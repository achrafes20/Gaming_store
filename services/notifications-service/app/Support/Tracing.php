<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Same OTLP/HTTP export as the other services' App\Support\Tracing, trimmed
 * down for a worker: there's no inbound HTTP request or outbound call to
 * propagate a header on, just one span per RabbitMQ message consumed —
 * a leaf span, continuing the trace started by whichever service published
 * the event (see orders-service/users-service's EventPublisher, which
 * carries the trace's `traceparent` inside the message body). See
 * docs/observability.md.
 */
class Tracing
{
    /** Runs $callback as a child span of the publisher's trace, then exports it to Jaeger. */
    public static function span(string $name, ?string $traceparent, callable $callback): mixed
    {
        $startedAt = microtime(true);
        $spanId = bin2hex(random_bytes(8));

        if ($traceparent && preg_match('/^00-([0-9a-f]{32})-([0-9a-f]{16})-[0-9a-f]{2}$/', $traceparent, $m)) {
            $traceId = $m[1];
            $parentSpanId = $m[2];
        } else {
            $traceId = bin2hex(random_bytes(16));
            $parentSpanId = null;
        }

        try {
            return $callback();
        } finally {
            self::export($traceId, $spanId, $parentSpanId, $name, $startedAt, microtime(true));
        }
    }

    private static function export(string $traceId, string $spanId, ?string $parentSpanId, string $name, float $startedAt, float $endedAt): void
    {
        if (! config('services.jaeger_otlp_url')) {
            return;
        }

        $span = [
            'traceId' => $traceId,
            'spanId' => $spanId,
            'name' => $name,
            'kind' => 3, // SPAN_KIND_CONSUMER
            'startTimeUnixNano' => (string) (int) ($startedAt * 1e9),
            'endTimeUnixNano' => (string) (int) ($endedAt * 1e9),
        ];
        if ($parentSpanId) {
            $span['parentSpanId'] = $parentSpanId;
        }

        $payload = [
            'resourceSpans' => [[
                'resource' => [
                    'attributes' => [
                        ['key' => 'service.name', 'value' => ['stringValue' => config('app.name', 'notifications-service')]],
                    ],
                ],
                'scopeSpans' => [[
                    'scope' => ['name' => 'gaming-store'],
                    'spans' => [$span],
                ]],
            ]],
        ];

        try {
            Http::baseUrl(config('services.jaeger_otlp_url'))->timeout(1)->post('/v1/traces', $payload);
        } catch (\Throwable $e) {
            Log::debug('Failed to export trace span to Jaeger', ['error' => $e->getMessage()]);
        }
    }
}
