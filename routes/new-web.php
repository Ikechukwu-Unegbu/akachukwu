<?php

use Illuminate\Support\Facades\Route;

Route::prefix('new')->group(function () {
   Route::view('/', 'new.home');
   Route::view('/register', 'new.register');
   Route::view('/login', 'new.login');


   Route::view('/transaction', 'new.transaction');



   Route::view('/dashboard', 'new.dashboard');
   //services
   Route::view('/dashboard/service', 'new.services');
   Route::view('dashboard/services/artime', 'new.airtime');
   Route::view('dashboard/services/data', 'new.data');
   Route::view('dashboard/services/education', 'new.result');
   Route::view('dashboard/services/electricity', 'new.electricity');
   //settings 
   Route::view('/dashboard/settings', 'new.settings');
   Route::view('/dashboard/security', 'new.security');
   Route::view('/dashboard/referral', 'new.referral');

});