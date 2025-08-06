<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
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
    public function boot()
{
    View::composer('*', function ($view) {
        $cartProducts = [];

        if (Auth::check()) {
            $cartProducts = Cart::where('user_id', Auth::id())->get();
        }

        $view->with('cartProducts', $cartProducts);
    });
}
}
