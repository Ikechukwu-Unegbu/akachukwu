<?php

use Illuminate\Support\Facades\Route;

Route::prefix('new')->group(function () {
 

   Route::view('/transaction', 'new.transaction');




   Route::view('dashboard/services/kyc', 'new.kyc');
   Route::view('dashboard/services/status', 'new.status_modal');





});