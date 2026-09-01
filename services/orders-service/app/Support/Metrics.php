<?php

namespace App\Support;

use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\APC;
use Prometheus\Storage\InMemory;

/**
 * One CollectorRegistry per container, backed by APCu (see Dockerfile) so
 * every php-fpm worker in this container shares the same counters — a
 * scrape can land on any worker and still see what every other worker
 * recorded. Golden Signals: traffic + errors (counters), latency
 * (histogram) — see App\Http\Middleware\PrometheusMetrics — and saturation
 * (the DB connection gauge registered here, sampled at scrape time).
 *
 * Falls back to InMemory when apcu isn't loaded — the actual Docker/K8s
 * images always have it (see each Dockerfile), but the host PHP running
 * `php artisan test` locally (and, unless installed there too, a CI
 * runner) doesn't; every test hitting any route crashed with
 * `APCu extension is not loaded` before this fallback existed. InMemory
 * loses cross-worker sharing, which matters in production but not for a
 * single test process asserting business logic, not metrics persistence.
 */
class Metrics
{
    private static ?CollectorRegistry $registry = null;

    public static function registry(): CollectorRegistry
    {
        return self::$registry ??= new CollectorRegistry(extension_loaded('apcu') ? new APC : new InMemory);
    }

    public static function render(): string
    {
        return (new RenderTextFormat)->render(self::registry()->getMetricFamilySamples());
    }
}
