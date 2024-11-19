<?php

namespace App\Providers;

use Livewire\Livewire;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
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

        Livewire::setUpdateRoute(function ($handle) {
            $path = config('app.path').'/livewire/update';
            return Route::post($path, $handle)->middleware('web');
        });
        Livewire::setScriptRoute(function ($handle) {
            $path = config('app.path').'/livewire/livewire.js';
            return Route::get($path, $handle)->middleware('web');
        });
        URL::forceRootUrl(config('app.url'));
        URL::forceScheme(config('app.scheme','http'));
    }
    
}
