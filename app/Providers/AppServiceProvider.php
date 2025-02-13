<?php

namespace App\Providers;

use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Observers\UserWalletObserver;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

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

        if (Schema::hasTable('site_settings')) {
            View::share('settings', SiteSetting::find(1));
        } else {
            View::share('settings', null);
        }
        
        // View::share('settings', SiteSetting::find(1));

        User::observe(UserWalletObserver::class);
    }
    
}
