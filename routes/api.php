<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\API\AirtimeApiController;
use App\Http\Controllers\V1\API\NewtworkApiController;
use App\Http\Controllers\V1\API\UserProfileController;
use App\Http\Controllers\V1\API\Auth\RegisterUserController;
use App\Http\Controllers\V1\API\Auth\ChangePasswordController;
use App\Http\Controllers\V1\API\Auth\ForgotPasswordController;
use App\Http\Controllers\V1\API\Auth\AuthenticateUserController;
use App\Http\Controllers\V1\API\CableApiController;
use App\Http\Controllers\V1\API\DataApiController;
use App\Http\Controllers\V1\API\EducationController;
use App\Http\Controllers\V1\API\ElectricityApiController;
use App\Http\Controllers\V1\API\UserPinController;
use App\Http\Controllers\V1\PayVesselWebhookController;
use App\Http\Controllers\V1\WebhookController;
// use Livewire\Features\SupportFileUploads\FileUploadController;
use App\Http\Controllers\V1\API\FileUploadController;
use App\Http\Controllers\V1\API\TransactionsApiController;
use App\Http\Controllers\V1\API\UpgradeController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
Route::get('/user/{username}', [UserProfileController::class, 'show']);
Route::post('/change-password/{username}', [ChangePasswordController::class, 'changePassword']);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return User::with('virtualAccounts')->find(Auth::user()->id);
});

Route::group(['middleware' => ['auth:sanctum'],], function() {

    Route::post('/upload-avatar',[FileUploadController::class, 'store']);
    Route::post('/update-avatar',[FileUploadController::class, 'update']);

    Route::get('/upgrade-user', [UpgradeController::class, 'store']);

    Route::post('airtime/create', [AirtimeApiController::class, 'store']);
    Route::post('data/create', [DataApiController::class, 'store']);
    Route::post('cable/validate', [CableApiController::class, 'validateIUC']);
    Route::post('cable/create', [CableApiController::class, 'store']);

    Route::post('electricity/validate', [ElectricityApiController::class, 'validateMeterNo']);
    Route::post('electricity/create', [ElectricityApiController::class, 'store']);

    Route::post('pin/create', [UserPinController::class, 'create']);
    Route::post('pin/update', [UserPinController::class, 'update']);
    Route::post('pin/validate', [UserPinController::class, 'validatePin']);
    Route::post('epins/create', [EducationController::class, 'create']);

    // Route::get('/transactions', [TransactionsApiController::class, 'index']);
    // Route::get('/transactions/{id}', [TransactionsApiController::class, 'show']);
});
Route::get('/transactions', [TransactionsApiController::class, 'index']);
Route::get('/transactions/{id}', [TransactionsApiController::class, 'show']);


Route::post('networks', [NewtworkApiController::class, 'index']);
Route::post('datatypes', [DataApiController::class, 'index']);
Route::post('dataplans', [DataApiController::class, 'plan']);
Route::post('cables', [CableApiController::class, 'index']);
Route::post('cableplans', [CableApiController::class, 'plan']);
Route::post('electricity/discos', [ElectricityApiController::class, 'index']);
Route::post('webhook/monnify', WebhookController::class);
Route::post('webhook/payvessel', PayVesselWebhookController::class);
Route::post('exams', [EducationController::class, 'index']);
