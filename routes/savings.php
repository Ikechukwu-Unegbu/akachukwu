<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemUser\Savings\SavingsHomeController;
use App\Http\Controllers\SystemUser\Savings\SavingsPlansController;
use App\Http\Controllers\SystemUser\Savings\WithdrawalRequestController;


Route::prefix('admin')->as('admin.')->group(function () {
    Route::get('savings/home', [SavingsHomeController::class, 'dashboard']);
    Route::resource('savings', SavingsHomeController::class);

    Route::resource('saving-package', SavingsPlansController::class);

    Route::resource('withdraw', WithdrawalRequestController::class);
});
