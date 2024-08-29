<?php

use Illuminate\Support\Facades\Route;

Route::prefix('new')->group(function () {
   Route::view('/', 'new.home');
   Route::view('/register', 'new.register');
   Route::view('/login', 'new.login');

   Route::view('/dashboard', 'new.dashboard');
});