<?php

use App\Http\Controllers\V1\PaymentController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'user', 'card_funding']], function() {
    Route::get('fund-wallet', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('fund-wallet/process', [PaymentController::class, 'process'])->name('payment.process') ->middleware('throttle:fund-wallet');
});