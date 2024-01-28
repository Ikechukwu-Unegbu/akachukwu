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
        Route::get('register', App\Livewire\Admin\Auth\Register::class)->name('admin.auth.register');
    });

    Route::group(['middleware' => ['auth', 'admin']], function() {
        Route::get('dashboard', App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
        Route::get('utility/data', App\Livewire\Admin\Utility\Data\Index::class)->name('admin.utility.data');
        Route::get('utility/data/vendor/{vendor:id}/network/{network:id}/type/manage', App\Livewire\Admin\Utility\Data\Type\Index::class)->name('admin.utility.data.type');
        Route::get('utility/data/vendor/{vendor:id}/network/{network:id}/type/{type:id}/plan/manage', App\Livewire\Admin\Utility\Data\Plan\Index::class)->name('admin.utility.data.plan');
        Route::get('utility/data/vendor/{vendor:id}/network/{network:id}/edit', App\Livewire\Admin\Utility\Data\Edit::class)->name('admin.utility.data.network.edit');
        Route::get('utility/data/vendor/{vendor:id}/network/{network:id}/type/{type:id}/edit', App\Livewire\Admin\Utility\Data\Type\Edit::class)->name('admin.utility.data.type.edit');
        Route::get('utility/data/vendor/{vendor:id}/network/{network:id}/type/{type:id}/plan/create', App\Livewire\Admin\Utility\Data\Plan\Create::class)->name('admin.utility.data.plan.create');
        Route::get('utility/data/vendor/{vendor:id}/network/{network:id}/type/{type:id}/plan/{plan:id}/edit', App\Livewire\Admin\Utility\Data\Plan\Edit::class)->name('admin.utility.data.plan.edit');
        Route::get('utility/data/vendor/{vendor:id}/network/{network:id}/type/{type:id}/plan/{plan:id}/destroy', App\Livewire\Admin\Utility\Data\Plan\Delete::class)->name('admin.utility.data.plan.destroy');
    });

    // Route::get('/system/dashboard', [DashboardController::class, 'home'])->name('system.index');
});

