<?php

use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureAuthenticated;
use App\Http\Middleware\PrometheusMetrics;
use App\Http\Middleware\RequestTracing;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(RequestTracing::class);
        $middleware->append(PrometheusMetrics::class);
        $middleware->append(SecurityHeaders::class);

        $middleware->alias([
            'auth' => EnsureAuthenticated::class,
            'admin' => EnsureAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
