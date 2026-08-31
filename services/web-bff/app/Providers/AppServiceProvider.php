<?php

namespace App\Providers;

use App\Auth\SessionJwtGuard;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Auth::extend('session-jwt', fn () => new SessionJwtGuard);

        // Same limiters as the API services they front for — see SECURITY.md.
        RateLimiter::for('auth', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
        RateLimiter::for('checkout', fn (Request $request) => Limit::perMinute(10)->by($request->ip()));
    }
}
