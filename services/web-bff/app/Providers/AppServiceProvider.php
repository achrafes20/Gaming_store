<?php

namespace App\Providers;

use App\Auth\SessionJwtGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Auth::extend('session-jwt', fn () => new SessionJwtGuard());
    }
}
