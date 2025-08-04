<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\Customauth;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\CheckRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'customauth' => \App\Http\Middleware\Customauth::class,
            'checkRole' => \App\Http\Middleware\checkRole::class,
        ]);

        // Appliquer le middleware SetLocale globalement
         $middleware->web([
        \App\Http\Middleware\SetLocale::class,
    ]);
    })


    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
