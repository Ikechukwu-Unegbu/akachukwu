<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CardFundingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $siteSettings = SiteSetting::first();
    
        if (!$siteSettings || !$siteSettings->card_funding_status) {
            return redirect()->route('dashboard')->withErrors('Card funding is temporarily disabled. Please use the bank transfer feature instead or contact support for assistance.');
        }
    
        return $next($request);
    }
}
