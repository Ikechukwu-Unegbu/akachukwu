<?php

use App\Http\Controllers\V1\API\AnnouncementApiController;
use App\Http\Controllers\V1\API\BankTransferApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\WebhookController;
use App\Http\Controllers\V1\API\DataApiController;
use App\Http\Controllers\V1\API\UpgradeController;
use App\Http\Controllers\V1\API\UserPinController;
use App\Http\Controllers\V1\API\CableApiController;
use App\Http\Controllers\V1\API\ReferralController;
use App\Http\Controllers\V1\API\TransferController;
use App\Http\Controllers\V1\API\AppAssetsController;
use App\Http\Controllers\V1\API\EducationController;
use App\Http\Controllers\V1\API\AirtimeApiController;
use App\Http\Controllers\V1\API\FileUploadController;
use App\Http\Controllers\V1\PalmPayWebhookController;
use App\Http\Controllers\V1\API\NewtworkApiController;
use App\Http\Controllers\V1\API\UserProfileController;
// use Livewire\Features\SupportFileUploads\FileUploadController;
use App\Http\Controllers\V1\PayVesselWebhookController;
use App\Http\Controllers\V1\API\NotificationsController;
use App\Http\Controllers\V1\API\ElectricityApiController;
use App\Http\Controllers\V1\API\VirtualAccountController;
use App\Http\Controllers\V1\API\TransactionsApiController;
use App\Http\Controllers\V1\API\AccountManagementContorller;
use App\Http\Controllers\V1\API\Auth\RegisterUserController;
use App\Http\Controllers\V1\API\Auth\ChangePasswordController;
use App\Http\Controllers\V1\API\Auth\ForgotPasswordController;
use App\Http\Controllers\V1\API\Auth\AuthenticateUserController;
use App\Http\Controllers\V1\API\SiteSettingsApiController;

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

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::get('/user/{username}', [UserProfileController::class, 'show']);

Route::post('/verify-otp', [AuthenticateUserController::class, 'verifyOtp']);
Route::post('/resend-otp', [AuthenticateUserController::class, 'resendOtp']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return User::with('virtualAccounts')->find(Auth::user()->id);
});

Route::group(['middleware' => ['auth:sanctum'],], function() {
    //protected auth routes
    Route::post('logout', [AuthenticateUserController::class, 'logout']);
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword']);


    //image uload
    Route::post('/upload-avatar',[FileUploadController::class, 'store']);

    //fetch logos
    Route::get('logo', [AppAssetsController::class, 'getSpecifiedColumn']);


    Route::post('user', [UserProfileController::class, 'update']);
    Route::get('delete-user', [AccountManagementContorller::class, 'initiateAccountDeletion']);
    Route::post('confirm-delete', [AccountManagementContorller::class, 'confirmAccountDeletion']);

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
    Route::post('pin/reset', [UserPinController::class, 'resetPin']);
    Route::post('pin/reset-aux', [UserPinController::class, 'resetPinAux']);
    Route::post('epins/create', [EducationController::class, 'create']);

    Route::get('/transactions', [TransactionsApiController::class, 'index']);
    Route::get('/transactions/{id}', [TransactionsApiController::class, 'show']);

    Route::post('/transfer', TransferController::class);

    //notification
    Route::get('/notifications', [NotificationsController::class, 'index']);
    Route::get('/referrer', [ReferralController::class, 'index']);
    Route::get('/withdraw-bonus', [ReferralController::class, 'move_earning_to_wallet']);

    Route::prefix('virtual-accounts')->group(function () {
        Route::get('/', [VirtualAccountController::class, 'index']);
        Route::post('/create', [VirtualAccountController::class, 'store']);
        Route::post('/generate', [VirtualAccountController::class, 'createSpecificVirtualAccount']);
    });

    Route::prefix('bank')->group(function() {
        Route::post('query-account-number', [BankTransferApiController::class, 'queryAccountNumber']);
        Route::post('process-transaction', [BankTransferApiController::class, 'processTransfer']);
    });

    Route::get('announcements', [AnnouncementApiController::class, 'show']);
});




Route::post('networks', [NewtworkApiController::class, 'index']);
Route::post('datatypes', [DataApiController::class, 'index']);
Route::post('dataplans', [DataApiController::class, 'plan']);
Route::post('cables', [CableApiController::class, 'index']);
Route::post('cableplans', [CableApiController::class, 'plan']);
Route::post('electricity/discos', [ElectricityApiController::class, 'index']);
Route::post('webhook/monnify', WebhookController::class);
Route::post('webhook/payvessel', PayVesselWebhookController::class);
Route::post('exams', [EducationController::class, 'index']);
Route::get('banks', [VirtualAccountController::class, 'banks']);
Route::post('webhook/palmpay', PalmPayWebhookController::class)->name('webhook.palmpay');
Route::get('sitesetting', [SiteSettingsApiController::class, '__invoke']);
Route::get('active-virtual-accounts', [SiteSettingsApiController::class, 'activeVirtualAccounts']);
Route::get('bank-list', [BankTransferApiController::class, 'banks']);

