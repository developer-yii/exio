<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login');
        }

        if(Auth::user()->role_type != 2){
            return redirect()->route("front.home");
        }

        return $next($request);
    }

}
