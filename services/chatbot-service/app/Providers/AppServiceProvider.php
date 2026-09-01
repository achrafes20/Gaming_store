<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Each turn can mean several Gemini calls (one per tool-call round) —
        // caps both abuse and API cost. Same RateLimiter pattern as Phase 5's
        // 'auth'/'checkout' limiters.
        RateLimiter::for('chat', fn (Request $request) => Limit::perMinute(20)->by(
            $request->attributes->get('auth_user')['id'] ?? $request->ip()
        ));
    }
}
