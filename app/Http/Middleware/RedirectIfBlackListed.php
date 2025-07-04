<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfBlackListed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || $request->routeIs('login')) {
            return $next($request);
        }

        if ($user && $user->is_blacklisted) {
            auth()->logout();
            return redirect()->route('login')->withErrors('Your account has been blacklisted. Contact support.');
        }

        return $next($request);
    }
}
