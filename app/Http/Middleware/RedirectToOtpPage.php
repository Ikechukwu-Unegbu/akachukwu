<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class RedirectToOtpPage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (Otp::where('user_id', $user->id)->exists()) {
                if (!Route::is('otp')) {
                    return redirect()->route('otp');
                }
            }
        }
        return $next($request);
    }
}
