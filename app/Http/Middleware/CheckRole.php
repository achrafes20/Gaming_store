<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if($request->user()){
            if( in_array($request->user()->role,$roles ) ){
                return $next($request);
            }
        }
        else{
            return redirect('login');
        }
        return abort(403,'Unauthorized');
    }
}
//si tu veux avoir que user a plusieur role il faut créer un tableau pour route et autre pour user
