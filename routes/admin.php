<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SystemUser\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'admin'], function () {

    Route::group(['middleware' => 'guest'], function() {
        Route::get('/', App\Livewire\Admin\Auth\Login::class)->name('admin.auth.login');
        Route::get('login', App\Livewire\Admin\Auth\Login::class)->name('admin.auth.login');
        Route::get('login', App\Livewire\Admin\Auth\Register::class)->name('admin.auth.register');
    });

    Route::group(['middleware' => ['auth', 'admin']], function() {
        Route::get('dashboard', App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
    });

    Route::get('/system/dashboard', [DashboardController::class, 'home'])->name('system.index');
});

