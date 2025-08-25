<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Customauth2
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }
        elseif (!Auth::check()) {
            return redirect("/login"); 
        }

        return abort(403, 'Vous n\'avez pas les droits nécessaires pour accéder à cette page.');
    }
}
