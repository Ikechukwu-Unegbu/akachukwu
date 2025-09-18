<?php

use App\Http\Controllers\V1\API\AirtimeApiController;
use App\Http\Controllers\V1\API\AppAssetsController;
use App\Http\Controllers\V1\API\CableApiController;
use App\Http\Controllers\V1\API\CowrywiseSavingsController;
use App\Http\Controllers\V1\API\CowrywiseWalletController;
use App\Http\Controllers\V1\API\DataApiController;
use App\Http\Controllers\V1\API\EducationController;
use App\Http\Controllers\V1\API\FileUploadController;
use App\Http\Controllers\V1\API\NewtworkApiController;
use App\Http\Controllers\V1\API\NotificationsController;
use App\Http\Controllers\V1\API\ReferralController;
use App\Http\Controllers\V1\API\TransferController;
use App\Http\Controllers\V1\API\UpgradeController;
use App\Http\Controllers\V1\API\UserPinController;
use App\Http\Controllers\V1\API\UserProfileController;
use App\Http\Controllers\V1\PalmPayWebhookController;
use App\Http\Controllers\V1\PayVesselWebhookController;
use App\Http\Controllers\V1\WebhookController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
// use Livewire\Features\SupportFileUploads\FileUploadController;
use App\Http\Controllers\V1\API\Auth\AuthenticateUserController;
use App\Http\Controllers\V1\API\Auth\ChangePasswordController;
use App\Http\Controllers\V1\API\Auth\ForgotPasswordController;
use App\Http\Controllers\V1\API\Auth\RegisterUserController;
use App\Http\Controllers\V1\API\AccountManagementContorller;
use App\Http\Controllers\V1\API\AnnouncementApiController;
use App\Http\Controllers\V1\API\BankTransferApiController;
use App\Http\Controllers\V1\API\CowrywiseOnboardController;
use App\Http\Controllers\V1\API\ElectricityApiController;
use App\Http\Controllers\V1\API\KycVerificationController;
use App\Http\Controllers\V1\API\SiteSettingsApiController;
use App\Http\Controllers\V1\API\TransactionsApiController;
use App\Http\Controllers\V1\API\VirtualAccountController;
use App\Http\Controllers\V1\QuidaxController;
use App\Http\Controllers\V1\QuidaxParentUserController;
use App\Http\Controllers\V1\QuidaxWebhookController;
use App\Http\Controllers\V1\QuidaxSwapController;
use App\Http\Controllers\V1\ReferralContestApiController;
use App\Http\Controllers\V1\QuidaxTransferController;

/*
 * |--------------------------------------------------------------------------
 * | API Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register API routes for your application. These
 * | routes are loaded by the RouteServiceProvider and all of them will
 * | be assigned to the "api" middleware group. Make something great!
 * |
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

Route::group([
    'middleware' => ['auth:sanctum'],
], function () {
    // protected auth routes
    Route::post('logout', [AuthenticateUserController::class, 'logout']);
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword']);

    // image uload
    Route::post('/upload-avatar', [FileUploadController::class, 'store']);

    // fetch logos
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

    // notification
    Route::get('/notifications', [NotificationsController::class, 'index']);
    Route::get('/referrer', [ReferralController::class, 'index']);
Route::get('/withdraw-bonus', [ReferralController::class, 'move_earning_to_wallet']);

    // Referral Contest Routes
    Route::prefix('referral-contest')->group(function () {
        Route::get('/', [ReferralContestApiController::class, 'index']);
        Route::get('/leaderboard', [ReferralContestApiController::class, 'leaderboard']);
    });

    Route::prefix('virtual-accounts')->group(function () {
        Route::get('/', [VirtualAccountController::class, 'index']);
        Route::post('/create', [VirtualAccountController::class, 'store']);
        Route::post('/generate', [VirtualAccountController::class, 'createSpecificVirtualAccount']);
    });

    Route::prefix('bank')->group(function () {
        Route::post('query-account-number', [BankTransferApiController::class, 'queryAccountNumber']);
        Route::post('process-transaction', [BankTransferApiController::class, 'processTransfer']);
    });

    // KYC Verification Routes
    Route::prefix('kyc')->group(function () {
        Route::post('tier-1', [KycVerificationController::class, 'submitTier1']);
        Route::post('tier-2', [KycVerificationController::class, 'submitTier2']);
        Route::post('tier-3', [KycVerificationController::class, 'submitTier3']);
        Route::get('status', [KycVerificationController::class, 'getVerificationStatus']);
        Route::post('upload-document', [KycVerificationController::class, 'uploadDocument']);
    });

    Route::get('announcements', [AnnouncementApiController::class, 'show']);

    // Quidax Crypto Wallet Routes
    Route::prefix('quidax')->group(function () {
        // Account and Wallet Management
        Route::post('users/create', [QuidaxController::class, 'createUser']);
        Route::get('/account', [QuidaxController::class, 'getAccountInfo']);
        Route::get('/wallets', [QuidaxController::class, 'getUserWallets']);
        Route::post('/wallets-generate/{currency}', [QuidaxController::class, 'generateWalletAddress']);
        Route::post('/wallets-generates/{currency}', [QuidaxController::class, 'generateWalletAddressess']);
        Route::get('/wallets/{currency}', [QuidaxController::class, 'getWalletBalance']);
        Route::get('/balance-summary', [QuidaxController::class, 'getAccountBalanceSummary']);
        Route::get('/wallet-stats', [QuidaxController::class, 'getWalletStats']);

        Route::get('supported-currencies', [QuidaxController::class, 'getSupportedCurrencies']);
        // test requry
        Route::get('requry/{id}', [QuidaxController::class, 'reQueryDeposit']);

        // Swap quotation (testing only)
        Route::post('/swap/quote', [QuidaxSwapController::class, 'generateSwapQuotation']);
        
    });
});

/**
 * Quidax parent routes
 * **/
Route::get('parent/wallets', [QuidaxParentUserController::class, 'getAllParentWallets']);
Route::get('parent/wallets-generate', [QuidaxParentUserController::class, 'createAllParentWallets']);
Route::get('parent/wallets/{currency}', [QuidaxParentUserController::class, 'fetchGivenCurrencyPaymentAddress']);

Route::get('quidax-transfer', [QuidaxTransferController::class, 'transfer']);


Route::post('networks', [NewtworkApiController::class, 'index']);
Route::get('networks/{id}', [NewtworkApiController::class, 'show']);
Route::post('datatypes', [DataApiController::class, 'index']);
Route::post('dataplans', [DataApiController::class, 'plan']);
Route::post('cables', [CableApiController::class, 'index']);
Route::post('cableplans', [CableApiController::class, 'plan']);
Route::post('electricity/discos', [ElectricityApiController::class, 'index']);
Route::post('webhook/monnify', WebhookController::class);
Route::post('webhook/payvessel', PayVesselWebhookController::class);

Route::post('webhook/quidax', QuidaxWebhookController::class)->name('webhook.quidax');

Route::post('exams', [EducationController::class, 'index']);
Route::get('banks', [VirtualAccountController::class, 'banks']);
Route::post('webhook/palmpay', PalmPayWebhookController::class)->name('webhook.palmpay');
Route::get('sitesetting', [SiteSettingsApiController::class, '__invoke']);
Route::get('active-virtual-accounts', [SiteSettingsApiController::class, 'activeVirtualAccounts']);
Route::get('bank-list', [BankTransferApiController::class, 'banks']);

Route::prefix('investments')->middleware('auth:sanctum')->group(function () {
    Route::prefix('onboarding')->group(function () {
        // Route::get('fetch-all-accounts', [CowrywiseOnboardController::class, 'get']);
        Route::post('create', [CowrywiseOnboardController::class, 'create']);
        Route::post('profile/update', [CowrywiseOnboardController::class, 'updateProfile']);
        Route::get('account', [CowrywiseOnboardController::class, 'retrieveSingleAccount']);
        Route::get('portfolio', [CowrywiseOnboardController::class, 'getPortfolio']);
        Route::post('identity/update', [CowrywiseOnboardController::class, 'updateIdentity']);
        Route::post('address/update', [CowrywiseOnboardController::class, 'updateAddress']);
        Route::post('nok/update', [CowrywiseOnboardController::class, 'updateNextOfKin']);
        Route::post('bank/create', [CowrywiseOnboardController::class, 'addBank']);
    });

    Route::prefix('wallet')->group(function () {
        // Route::get('/', [CowrywiseWalletController::class, 'fetchAllWallet']);
        Route::get('/', [CowrywiseWalletController::class, 'fetchWallet']);
        // Route::post('/create', [CowrywiseWalletController::class, 'create']);
    });

    Route::prefix('savings')->group(function () {
        Route::get('/', [CowrywiseSavingsController::class, 'fetchAllSavings']);
        Route::get('rates', [CowrywiseSavingsController::class, 'getSavingRates']);
        Route::get('/{savingId}/fetch-savings', [CowrywiseSavingsController::class, 'retrieveSingleSavings']);
        Route::post('/create', [CowrywiseSavingsController::class, 'createSavings']);
        Route::post('{savingId}/fund', [CowrywiseSavingsController::class, 'fundSavings']);
        Route::get('{savingId}/performance', [CowrywiseSavingsController::class, 'getPerformance']);
        Route::post('{savingId}/withdraw', [CowrywiseSavingsController::class, 'withdrawToWallet']);
    });
});
