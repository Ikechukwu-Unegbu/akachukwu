<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SystemUser\ActivityLog\ActivitLogController;
use App\Http\Controllers\SystemUser\ActivityLog\ActivityHeatMapController;


Route::prefix('admin')->as('admin.')->group(function () {

    Route::resource('logs', ActivitLogController::class)->name('index', 'logs');
    Route::resource('map', ActivityHeatMapController::class);
});
