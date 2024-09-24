<?php

use Illuminate\Support\Facades\Route;

Route::prefix('new')->group(function () {
 

   Route::view('/transaction', 'new.transaction');




   Route::view('dashboard/services/otp', 'new.otp');
   Route::view('dashboard/services/status', 'new.status_modal');


   Route::view('/dashboard/profile', 'new.profile');
   Route::view('/dashboard/support', 'new.support')->name('new.support');

   //settings 
   Route::view('/dashboard/settings', 'new.settings')->name('new.setting');
   Route::view('/dashboard/security', 'new.security')->name('new.security');
   Route::view('/dashboard/referral', 'new.referral')->name('new.referral');

});