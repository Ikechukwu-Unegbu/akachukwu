<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Impersonate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('impersonate')) {
            $impersonatedUserId = Session::get('impersonate');
            $impersonatedUser = \App\Models\User::find($impersonatedUserId);
            
            Auth::onceUsingId($impersonatedUser->id);
        }
        
        return $next($request);
    }
}
