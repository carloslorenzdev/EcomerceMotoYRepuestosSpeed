<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfPasswordNotSet
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->password_set) {
            if (!$request->routeIs('password.set') && !$request->routeIs('logout') && !$request->routeIs('password.set.store')) {
                return redirect()->route('password.set');
            }
        }

        return $next($request);
    }
}
