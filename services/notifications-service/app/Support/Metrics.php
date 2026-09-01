<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\InMemory;

/**
 * This worker has no HTTP server for Prometheus to scrape a /metrics
 * endpoint from (it's a long-running CLI consumer — see
 * app/Console/Commands/ConsumeEvents.php), so it uses the standard
 * Prometheus pattern for that case: push to a Pushgateway instead, which
 * Prometheus scrapes like any other target. `promphp/prometheus_client_php`
 * doesn't ship a Pushgateway client, so this pushes the same text-exposition
 * format Metrics::render() already produces via a plain HTTP PUT — the
 * Pushgateway's REST API, no extra dependency needed.
 */
class Metrics
{
    private static ?CollectorRegistry $registry = null;

    public static function registry(): CollectorRegistry
    {
        // InMemory, not APCu: this is a single long-running CLI process
        // (not multiple php-fpm workers sharing a container), so
        // process-local memory is all that's needed between messages.
        return self::$registry ??= new CollectorRegistry(new InMemory);
    }

    /** Push whatever this process has accumulated so far to the shared Pushgateway. */
    public static function push(): void
    {
        // Pushgateway's text-format parser rejects a body with no trailing
        // newline ("unexpected end of input stream") — RenderTextFormat
        // doesn't guarantee one after the last line. Found by a real 400
        // from a real PUT, not by reading the exposition format spec.
        $body = rtrim((new RenderTextFormat)->render(self::registry()->getMetricFamilySamples()))."\n";
        $instance = gethostname() ?: 'unknown';

        try {
            // ->throw() so a non-2xx (the pushgateway rejecting the body,
            // say) actually reaches the catch block below — Laravel's Http
            // client otherwise returns it as a normal response and silently
            // does nothing, which is how this failed silently the first
            // time (no exception, so nothing was ever logged).
            Http::withBody($body, 'text/plain')
                ->timeout(2)
                ->put(rtrim(config('services.pushgateway_url'), '/')."/metrics/job/notifications_service/instance/{$instance}")
                ->throw();
        } catch (\Throwable $e) {
            Log::warning('Failed to push metrics to Pushgateway', ['error' => $e->getMessage()]);
        }
    }
}
