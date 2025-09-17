<?php

use App\Models\User;
use App\Models\Data\DataPlan;
use App\Notifications\WelcomeEmail;
use Illuminate\Support\Facades\Route;
use App\Services\Payment\MonnifyService;
use  App\Http\Controllers\TestController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\MtnDevController;
use App\Http\Controllers\V1\PinController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\V1\AdminController;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\V1\SettingsController;
use App\Http\Controllers\Blog\BlogPageController;
use App\Notifications\AdminDebitUserNotification;
use App\Http\Controllers\V1\ApiResponseController;
use App\Http\Controllers\V1\TransactionController;
use App\Http\Controllers\ProfileSettingsController;
use App\Http\Controllers\V1\Utilities\TVController;
use App\Http\Controllers\UpgradeToResellerController;
use App\Http\Controllers\V1\Utilities\DataController;
use App\Http\Controllers\V1\BonusWithdrawalController;
use App\Http\Controllers\V1\Utilities\AirtimeController;
use App\Http\Controllers\V1\Utilities\ElectricityController;
use App\Http\Controllers\V1\Education\ResultCheckerController;
use App\Http\Controllers\SystemUser\WalletFundingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/ref/{username}', function($username){
    // dd($username);
    $user = User::where('username', $username)->firstOrFail();
    // $service = new MonnifyService();
    // return $service->getAllVirtualAccountsOfGivenUser($user->username);
    dd($user);
});

Route::get('/view-crypto-log', function(){
    $log = \App\Models\Payment\CryptoTransactionsLog::all();
    dd($log);
});

Route::get('savings', function () {
    return view('savings');
})->name('savings');

Route::get('vasSave', function () {
    return view('vasSave');
})->name('vasSave');


Route::get('vasTarget', function () {
    return view('vasTarget');
})->name('vasTarget');


Route::get('vasTargetDashboard', function () {
    return view('vasTargetDashboard');
})->name('vasTargetDashboard');


Route::get('vasfixeddashboard', function () {
    return view('vasfixeddashboard');
})->name('vasfixeddashboard');

Route::get('vastargetcreateplan', function () {
    return view('vastargetcreateplan');
})->name('vastargetcreateplan');



Route::get('vassavedashboard', function () {
    return view('vassavedashboard');
})->name('vassavedashboard');






Route::get('restrained', function () {
    return view('errors.restrained');
})->name('restrained');


Route::get('leaderboard', function () {
    return view('/leaderboard');
})->name('leaderboard');

Route::get('referral', function () {
    return view('/referral');
})->name('referral');





Route::middleware(['testing'])->group(function () {
    Route::get('/', function () {
        return view('pages.home.home');
    });
    Route::get('/privacy-policy', [PagesController::class, 'privacy_policy'])->name('privacy');
    Route::get('/refund-policy', [PagesController::class, 'refund_policy'])->name('refund');
    Route::get('/terms', [PagesController::class, 'terms'])->name('terms');
    Route::get('/faq', [PagesController::class, 'faq'])->name('faq');
    Route::get('/gen', [TestController::class, 'gen']);
    Route::get('settings/support', [SettingsController::class, 'support'])->name('settings.support');
    Route::view('/about', 'pages.about')->name('pages.about');
Route::resource('blog', BlogPageController::class)->parameters(['blog' => 'slug']);

});


Route::middleware(['auth', 'verified', 'user', 'otp', 'testing', 'impersonate', 'blacklist'])->group(function () {

    Route::get('/airtime', [AirtimeController::class, 'index'])->name('airtime.index');
    Route::get('/data', [DataController::class, 'index'])->name('data.index');
    Route::get('/electricity', [ElectricityController::class, 'index'])->name('electricity.index');
    Route::get('/cable-tv', [TVController::class, 'index'])->name('cable.index');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/services', function () {
        return view('pages.utilities.services');
    })->name('services');

    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('settings/referrals', [SettingsController::class, 'referral'])->name('settings.referral');
    Route::get('settings/credentials', [SettingsController::class, 'credentials'])->name('settings.credentials');

    Route::get('/withdraw', BonusWithdrawalController::class)->name('withdrawal');

    Route::get('settings/support', [SettingsController::class, 'support'])->name('settings.support');
    Route::get('settings/kyc', [SettingsController::class, 'kyc'])->name('settings.kyc');


    Route::get('transactions', TransactionController::class)->name('transactions');
    Route::get('api-response/{type}/{transaction_id}', ApiResponseController::class)->name('api.response');

    Route::get('transactions/airtime', \App\Livewire\User\Transaction\Airtime\Index::class)->name('user.transaction.airtime');
    Route::get('transactions/airtime/{airtime:transaction_id}', \App\Livewire\User\Transaction\Airtime\Receipt::class)->name('user.transaction.airtime.receipt');

    Route::get('transactions/data', \App\Livewire\User\Transaction\Data\Index::class)->name('user.transaction.data');
    Route::get('transactions/data/{data:transaction_id}', \App\Livewire\User\Transaction\Data\Receipt::class)->name('user.transaction.data.receipt');

    Route::get('transactions/electricity', \App\Livewire\User\Transaction\Electricity\Index::class)->name('user.transaction.electricity');
    Route::get('transactions/electricity/{electricity:transaction_id}', \App\Livewire\User\Transaction\Electricity\Receipt::class)->name('user.transaction.electricity.receipt');

    Route::get('transactions/cable', \App\Livewire\User\Transaction\Cable\Index::class)->name('user.transaction.cable');
    Route::get('transactions/cable/{cable:transaction_id}', \App\Livewire\User\Transaction\Cable\Receipt::class)->name('user.transaction.cable.receipt');

    Route::get('transactions/wallet', \App\Livewire\User\Transaction\Wallet\Index::class)->name('user.transaction.wallet');

    Route::get('result-checker', [ResultCheckerController::class, 'index'])->name('education.result.index');
    Route::get('transactions/result-checker', \App\Livewire\User\Transaction\Education\ResultChecker\Index::class)->name('user.transaction.education');
    Route::get('transactions/result-checker/{checker:transaction_id}', \App\Livewire\User\Transaction\Education\ResultChecker\Receipt::class)->name('user.transaction.education.receipt');
    Route::get('otp/verify', function () {
        return view('auth.otp');
    })->name('otp');

    Route::post('/upgrade-to-reseller', UpgradeToResellerController::class)->name('reseller-upgrade');
    // Route::get('money-transfer', \App\Livewire\User\MoneyTransfer\Index::class)->name('user.money-transfer');

    Route::prefix('user-pin')->as('pin.')->group(function () {
        Route::post('send-otp', [PinController::class, 'sendOtp'])->name('send-otp');
        Route::post('verify-otp', [PinController::class, 'verifyOtp'])->name('verify-otp');
        Route::post('update', [PinController::class, 'update'])->name('update');
    });
});

Route::post('/impersonate/{user}', [AdminController::class, 'impersonate'])->name('impersonate.start');
Route::post('/stop-impersonating', [AdminController::class, 'stopImpersonating'])->name('impersonate.stop');


Route::middleware(['auth', 'impersonate'])->group(function () {
    Route::get('/profile', [ProfileSettingsController::class, 'edit'])->name('profile.edit');
    Route::get('/pins', [ProfileSettingsController::class, 'editPin'])->name('profile.pin');
    Route::post('/profile', [ProfileSettingsController::class, 'update'])->name('profile.update');
});

Route::post('update-password', [SettingsController::class, 'updatePassword'])->name('update.password');

// Route::get('/process-palmpay', function () {
//     return \App\Services\Money\PalmPayService::createSpecificVirtualAccount(auth()->user());
// });


// Route::post('webhook/quidax', QuidaxWebhookController::class)->name('webhook.quidax');

Route::get('/mtn/subscription-plans', [MtnDevController::class, 'listSubscriptionPlans']);

Route::get('system-user/wallet-funding/{transaction_id}', [WalletFundingController::class, 'show'])->name('system-user.wallet-funding.show');

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/feature.php';
require __DIR__ . '/savings.php';
require __DIR__ . '/logger.php';


