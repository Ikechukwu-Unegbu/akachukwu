<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ThrottleUtilityTransaction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $actionName = 'default', $ttl = 60): Response
    {
        $userId = auth()->id();
        $key = $actionName . '_' . $userId;

        if (Cache::has($key)) {
            return response()->json([
                'message' => 'You are performing this action too frequently. Please wait a minute.',
            ], 429);
        }

        Cache::put($key, true, $ttl);
        sleep(random_int(1, 4));

        return $next($request);
    }
}
