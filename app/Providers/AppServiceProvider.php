<?php

namespace App\Providers;

use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;
use App\Observers\UserWalletObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share('settings', SiteSetting::find(1));
        User::observe(UserWalletObserver::class);
    }
    
}
