<?php

use App\Http\Controllers\Blog\BlogPageController;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\TestController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\V1\AdminController;
use App\Http\Controllers\V1\Utilities\TVController;
use App\Http\Controllers\ProfileSettingsController;
use App\Http\Controllers\UpgradeToResellerController;
use App\Http\Controllers\V1\ApiResponseController;
use App\Http\Controllers\V1\Utilities\DataController;
use App\Http\Controllers\V1\Utilities\AirtimeController;
use App\Http\Controllers\V1\Utilities\ElectricityController;
use App\Http\Controllers\V1\Education\ResultCheckerController;
use App\Http\Controllers\V1\SettingsController;
use App\Http\Controllers\V1\TransactionController;

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

Route::get('/debug-config', function () {
    if (file_exists(base_path('.env'))) {
        echo '.env file exists';
    } else {
        echo '.env file does not exist';
    }
    
    return response()->json([
        'env'=> env('APP_ENV'),
        'bucket'=>env('DO_BUCKET'),
        'env_key' => env('DO_ACCESS_KEY_ID'),
        'do_ur'=>env('DO_URL'),
        'config'  => config('services.digitalocean'),
    ]);
    // $envPath = base_path('.env');
    // if (file_exists($envPath)) {
    //     return response()->json([
    //         'status' => 'success',
    //         'content' => file_get_contents($envPath),
    //     ]);
    // } else {
    //     return response()->json([
    //         'status' => 'error',
    //         'message' => '.env file does not exist',
    //     ]);
    // }

});


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


Route::middleware(['auth', 'verified', 'user', 'otp', 'testing', 'impersonate'])->group(function () {

    Route::get('/airtime', [AirtimeController::class, 'index'])->name('airtime.index');
    Route::get('/data', [DataController::class, 'index'])->name('data.index');
    Route::get('/electricity', [ElectricityController::class, 'index'])->name('electricity.index');
    Route::get('/cable-tv', [TVController::class, 'index'])->name('cable.index');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/services', function () {
        return view('pages.utilities.services');
    })->name('services');

    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('settings/referrals', [SettingsController::class, 'referral'])->name('settings.referral');
    Route::get('settings/credentials', [SettingsController::class, 'credentials'])->name('settings.credentials');

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
});

Route::post('/impersonate/{user}', [AdminController::class, 'impersonate'])->name('impersonate.start');
Route::post('/stop-impersonating', [AdminController::class, 'stopImpersonating'])->name('impersonate.stop');


Route::middleware(['auth', 'impersonate'])->group(function () {
    Route::get('/profile', [ProfileSettingsController::class, 'edit'])->name('profile.edit');
    Route::get('/pins', [ProfileSettingsController::class, 'editPin'])->name('profile.pin');
    Route::post('/profile', [ProfileSettingsController::class, 'update'])->name('profile.update');
});

Route::post('update-password', [SettingsController::class, 'updatePassword'])->name('update.password');

//Blogs



require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/feature.php';
