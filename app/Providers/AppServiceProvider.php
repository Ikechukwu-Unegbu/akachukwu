<?php

namespace App\Providers;

use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;
use App\Observers\UserWalletObserver;
use Illuminate\Support\ServiceProvider;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redis;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('fund-wallet', function ($request) {
            return Limit::perMinute(1)->by(optional($request->user())->id ?: $request->ip());
        });
        View::share('settings', SiteSetting::find(1));
        User::observe(UserWalletObserver::class);
    }
    
}
