<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckSessionSecurity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $currentIp = $request->ip();
            $currentUserAgent = $request->userAgent();

            // If session IP is not set, set it now (e.g. just logged in)
            if (!Session::has('session_ip')) {
                Session::put('session_ip', $currentIp);
                Session::put('session_user_agent', $currentUserAgent);
            } else {
                // Check if IP or User Agent changed
                if (Session::get('session_ip') !== $currentIp || Session::get('session_user_agent') !== $currentUserAgent) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')->with('error', 'Sesión expirada por seguridad. Se detectó un cambio de red o dispositivo. Por favor, inicie sesión nuevamente.');
                }
            }
        }

        return $next($request);
    }
}
