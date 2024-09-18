<?php

use Illuminate\Support\Facades\Route;

Route::prefix('new')->group(function () {
 

   Route::view('/transaction', 'new.transaction');



   Route::view('/dashboard', 'new.dashboard');
   //services
   Route::view('/dashboard/service', 'new.services');
   Route::view('dashboard/services/artime', 'new.airtime');
   Route::view('dashboard/services/data', 'new.data');
   Route::view('dashboard/services/cable', 'new.cable');
   Route::view('dashboard/services/otp', 'new.otp');
   Route::view('dashboard/services/status', 'new.status_modal');
   Route::view('dashboard/services/education', 'new.result');
   Route::view('dashboard/services/electricity', 'new.electricity');

   Route::view('/dashboard/profile', 'new.profile');
   Route::view('/dashboard/support', 'new.support');

   //settings 
   Route::view('/dashboard/settings', 'new.settings');
   Route::view('/dashboard/security', 'new.security')->name('new.security');
   Route::view('/dashboard/referral', 'new.referral')->name('new.referral');

});