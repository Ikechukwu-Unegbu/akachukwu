<?php

use App\Http\Controllers\V1\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('fund-wallet', [PaymentController::class, 'index'])->name('payment.index');