<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {       
        if (Auth::check()) 
            if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) 
                return redirect()->back()->withErrors("You are not allow to access this page!");

        return $next($request);
    }
}
