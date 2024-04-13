<?php

use App\Http\Controllers\V1\API\Auth\RegisterUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\API\Auth\AuthenticateUserController;
use App\Http\Controllers\V1\API\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [RegisterUserController::class, 'register']);
Route::post('/login', [AuthenticateUserController::class, 'login']);
Route::post('logout', [AuthenticateUserController::class, 'logout']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::get('/ping', function(){
    var_dump('we here');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
