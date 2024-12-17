<?php

use App\Http\Controllers\Blog\BlogController;
use App\Http\Controllers\Blog\CategoryController;
use App\Http\Controllers\Blog\FaqController;
use App\Http\Controllers\Blog\MediaController;
use App\Http\Controllers\Blog\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SystemUser\DashboardController;
use App\Http\Controllers\SystemUser\SiteSettingsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemUser\UserCrdDbtController;
use App\Livewire\Admin\CrdDbt\Create as CrdDbtCreate;
use App\Livewire\Admin\Settings\AppLogos;
use App\Livewire\Component\Admin\SiteSettings;
use App\Models\SiteSettings as ModelsSiteSettings;
use App\Models\User;
use App\Services\Account\UserTransactionsAuditService;

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

Route::group(['prefix' => 'admin'], function () {

    Route::get('audit/{username}', function($username){
        $service = new UserTransactionsAuditService();
        $user = User::where('username', $username)->first();
        return $service->calculateUserBalance($user);
    });
    Route::group(['middleware' => ['guest', 'testing']], function() {
        Route::get('/', App\Livewire\Admin\Auth\Login::class)->name('admin.auth.login');
        Route::get('login', App\Livewire\Admin\Auth\Login::class)->name('admin.auth.login');
        Route::get('register', App\Livewire\Admin\Auth\Register::class)->name('admin.auth.register');
    });

    Route::group(['as' => 'admin.', 'middleware' => ['auth', 'admin', 'testing', 'impersonate']], function() {
       Route::resource('post', PostController::class);
       Route::resource('blog', BlogController::class);
       Route::resource('category', CategoryController::class);
       Route::resource('media', MediaController::class);
       Route::resource('faq', FaqController::class);
    });

    
    Route::group(['middleware' => ['auth', 'admin', 'testing', 'impersonate']], function() {
        ## Dashboard Route
        Route::get('dashboard', App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');

        ## Utility Routes
        ## Utility - Data Routes 
        Route::get('utility/airtime', App\Livewire\Admin\Utility\Airtime\Index::class)->name('admin.utility.airtime');
        Route::get('utility/airtime/vendor/{vendor:id}/discounts', App\Livewire\Admin\Utility\Airtime\Discount::class)->name('admin.utility.airtime.discount');
        ## Utility - Data Routes 
        Route::get('utility/data', App\Livewire\Admin\Utility\Data\Index::class)->name('admin.utility.data');
        Route::get('utility/data/vendor/{vendor:id}/discounts', App\Livewire\Admin\Utility\Data\Discount::class)->name('admin.utility.data.discount');
        Route::get('utility/data/vendor/{vendor:id}/network/{network:id}/edit', App\Livewire\Admin\Utility\Data\Edit::class)->name('admin.utility.data.network.edit');
        Route::get('utility/data/vendor/{vendor:id}/network/{network:id}/type/manage', App\Livewire\Admin\Utility\Data\Type\Index::class)->name('admin.utility.data.type');
        Route::get('utility/data/vendor/{vendor:id}/network/{network:id}/type/{type:id}/edit', App\Livewire\Admin\Utility\Data\Type\Edit::class)->name('admin.utility.data.type.edit');
        Route::get('utility/data/vendor/{vendor:id}/network/{network:id}/type/{type:id}/plan/manage', App\Livewire\Admin\Utility\Data\Plan\Index::class)->name('admin.utility.data.plan');
        Route::get('utility/data/vendor/{vendor:id}/network/{network:id}/type/{type:id}/plan/create', App\Livewire\Admin\Utility\Data\Plan\Create::class)->name('admin.utility.data.plan.create');
        Route::get('utility/data/vendor/{vendor:id}/network/{network:id}/type/{type:id}/plan/{plan:id}/edit', App\Livewire\Admin\Utility\Data\Plan\Edit::class)->name('admin.utility.data.plan.edit');
        Route::get('utility/data/vendor/{vendor:id}/network/{network:id}/type/{type:id}/plan/{plan:id}/destroy', App\Livewire\Admin\Utility\Data\Plan\Delete::class)->name('admin.utility.data.plan.destroy');
        ## Utility - Cable Routes
        Route::get('utility/cable', App\Livewire\Admin\Utility\Cable\Index::class)->name('admin.utility.cable');
        Route::get('utility/cable/vendor/{vendor:id}/discounts', App\Livewire\Admin\Utility\Cable\Discount::class)->name('admin.utility.cable.discount');
        Route::get('utility/cable/vendor/{vendor:id}/cable/{cable:id}/edit', App\Livewire\Admin\Utility\Cable\Edit::class)->name('admin.utility.cable.edit');
        Route::get('utility/cable/vendor/{vendor:id}/cable/{cable:id}/manage', App\Livewire\Admin\Utility\Cable\Plan\Index::class)->name('admin.utility.cable.plan');
        Route::get('utility/cable/vendor/{vendor:id}/cable/{cable:id}/plan/create', App\Livewire\Admin\Utility\Cable\Plan\Create::class)->name('admin.utility.cable.plan.create');
        Route::get('utility/cable/vendor/{vendor:id}/cable/{cable:id}/plan/{plan:id}/edit', App\Livewire\Admin\Utility\Cable\Plan\Edit::class)->name('admin.utility.cable.plan.edit');
        Route::get('utility/cable/vendor/{vendor:id}/cable/{cable:id}/plan/{plan:id}/destroy', App\Livewire\Admin\Utility\Cable\Plan\Delete::class)->name('admin.utility.cable.plan.destroy');
        ## Utility - Electricity
        Route::get('utility/electricity', App\Livewire\Admin\Utility\Electricity\Index::class)->name('admin.utility.electricity');
        Route::get('utility/electricity/vendor/{vendor:id}/discounts', App\Livewire\Admin\Utility\Electricity\Discount::class)->name('admin.utility.electricity.discount');
        Route::get('utility/electricity/vendor/{vendor:id}/create', App\Livewire\Admin\Utility\Electricity\Create::class)->name('admin.utility.electricity.create');
        Route::get('utility/electricity/vendor/{vendor:id}/electricity/{electricity:id}/edit', App\Livewire\Admin\Utility\Electricity\Edit::class)->name('admin.utility.electricity.edit');
        Route::post('utility/electricity/vendor/{vendor:id}/electricity/{electricity:id}/edit', [App\Livewire\Admin\Utility\Electricity\Edit::class, 'update'])->name('admin.utility.electricity.update');

    
        ## Transaction Routes
        Route::get('transaction', App\Livewire\Admin\Transaction\Index::class)->name('admin.transaction');
        ## Transaction - Airtime
        Route::get('transaction/airtime', App\Livewire\Admin\Transaction\Airtime\Index::class)->name('admin.transaction.airtime');
        Route::get('transaction/airtime/{airtime:id}/show', App\Livewire\Admin\Transaction\Airtime\Show::class)->name('admin.transaction.airtime.show');

        ## Transaction - Data
        Route::get('transaction/data', App\Livewire\Admin\Transaction\Data\Index::class)->name('admin.transaction.data');
        Route::get('transaction/data/{data:id}/show', App\Livewire\Admin\Transaction\Data\Show::class)->name('admin.transaction.data.show');

        ## Transaction - Cable
        Route::get('transaction/cable', App\Livewire\Admin\Transaction\Cable\Index::class)->name('admin.transaction.cable');
        Route::get('transaction/cable/{cable:id}/show', App\Livewire\Admin\Transaction\Cable\Show::class)->name('admin.transaction.cable.show');

        ## Transaction - Electricity
        Route::get('transaction/electricity', App\Livewire\Admin\Transaction\Electricity\Index::class)->name('admin.transaction.electricity');
        Route::get('transaction/electricity/{electricity:id}/show', App\Livewire\Admin\Transaction\Electricity\Show::class)->name('admin.transaction.electricity.show');

        ## Transaction - Result-Checker
        Route::get('transaction/result-checker', App\Livewire\Admin\Transaction\ResultChecker\Index::class)->name('admin.transaction.result-checker');
        Route::get('transaction/result-checker/{resultChecker:id}/show', App\Livewire\Admin\Transaction\ResultChecker\Show::class)->name('admin.transaction.result-checker.show');

        ## Transaction - Reseller
        Route::get('transaction/resellers', App\Livewire\Admin\Transaction\Reseller\Index::class)->name('admin.transaction.reseller');
        Route::get('transaction/resellers/create', App\Livewire\Admin\Transaction\Reseller\Create::class)->name('admin.transaction.reseller.create');
        Route::get('transaction/resellers/{reseller:id}/edit', App\Livewire\Admin\Transaction\Reseller\Edit::class)->name('admin.transaction.reseller.edit');
        Route::get('transaction/resellers/{reseller:id}/delete', App\Livewire\Admin\Transaction\Reseller\Delete::class)->name('admin.transaction.reseller.delete');

        Route::get('transaction/query-vendors', App\Livewire\Admin\Transaction\QueryTransaction::class)->name('admin.transaction.query-vendor');

        ## API Routes
        ## API - Vendor
        Route::get('api/vendors', App\Livewire\Admin\Api\Vendor\Index::class)->name('admin.api.vendor');
        Route::get('api/vendors/account', App\Livewire\Admin\Api\Vendor\Account::class)->name('admin.api.vendor.account');
        Route::get('api/vendor/{vendor:id}/show', App\Livewire\Admin\Api\Vendor\Show::class)->name('admin.api.vendor.show');
        Route::get('api/vendor/{vendor:id}/edit', App\Livewire\Admin\Api\Vendor\Edit::class)->name('admin.api.vendor.edit');

        Route::get('api/vendors/services', App\Livewire\Admin\Api\Vendor\Service::class)->name('admin.api.vendor.service');

        ## API - Payment
        Route::get('api/payments', App\Livewire\Admin\Api\Payment\Index::class)->name('admin.api.payment');
        Route::get('api/payment/{payment:id}/show', App\Livewire\Admin\Api\Payment\Show::class)->name('admin.api.payment.show');
        Route::get('api/payment/{payment:id}/edit', App\Livewire\Admin\Api\Payment\Edit::class)->name('admin.api.payment.edit');

        ## HR Routes
        ## HR - User
        Route::get('hr/users', App\Livewire\Admin\Hr\User\Index::class)->name('admin.hr.user');
        Route::get('hr/user/{user:username}/show', App\Livewire\Admin\Hr\User\Show::class)->name('admin.hr.user.show');
        Route::get('hr/user/{user:username}/upgrade', App\Livewire\Admin\Hr\User\Upgrade::class)->name('admin.hr.user.upgrade');
        Route::get('crd-dbt', CrdDbtCreate::class)->name('admin.crd-dbt');
        Route::post('admin/crdt-dbt', [UserCrdDbtController::class, 'store'])->name('admin.crdt-dbt.store');

        ## HR - Administrators
        Route::get('hr/administrators', App\Livewire\Admin\Hr\Administrator\Index::class)->name('admin.hr.administrator');
        Route::get('hr/administrator/{user:username}/show', App\Livewire\Admin\Hr\Administrator\Show::class)->name('admin.hr.administrator.show');
        Route::get('hr/administrator/{user:username}/upgrade', App\Livewire\Admin\Hr\Administrator\Upgrade::class)->name('admin.hr.administrator.upgrade');

        ## HR - Resellers
        Route::get('hr/resellers', App\Livewire\Admin\Hr\Reseller\Index::class)->name('admin.hr.reseller');
        Route::get('hr/resellers/{user:username}/show', App\Livewire\Admin\Hr\Reseller\Show::class)->name('admin.hr.reseller.show');
        Route::get('hr/resellers/{user:username}/upgrade', App\Livewire\Admin\Hr\Reseller\Upgrade::class)->name('admin.hr.reseller.upgrade');

        ## Settings Routes
        ## Settings - Role
        Route::get('settings/roles', App\Livewire\Admin\Role\Index::class)->name('admin.settings.role');
        Route::get('settings/role/create', App\Livewire\Admin\Role\Create::class)->name('admin.settings.role.create');
        Route::get('settings/role/{role:id}/edit', App\Livewire\Admin\Role\Edit::class)->name('admin.settings.role.edit');
        Route::get('settings/role/{role:id}/assign', App\Livewire\Admin\Role\Assign::class)->name('admin.settings.role.assign');
        Route::get('settings/role/{role:id}/user/{user:username}/permission/edit', App\Livewire\Admin\Role\Permission\Edit::class)->name('admin.settings.role.permission.edit');

        Route::get('profile', App\Livewire\Admin\Profile\Index::class)->name('admin.settings.profile');

        //site params
        Route::get('site-setting', SiteSettings::class)->name('admin.site.settings');
        Route::post('site-setting', [SiteSettingsController::class, 'update'])->name('admin.site.update');
        Route::get('/announcement', App\Livewire\Admin\Announcement\Create::class)->name('admin.announcement');
        Route::get('/announcement/index', App\Livewire\Admin\Announcement\Index::class)->name('admin.announcement.index');
        Route::get('/announcement/{announcement:id}/edit', App\Livewire\Admin\Announcement\Edit::class)->name('admin.announcement.edit');
        Route::get('/app-logos', AppLogos::class)->name('admin.app-logos');


        ## Activity Log
        Route::get('activities', App\Livewire\Admin\Activities\Index::class)->name('admin.activity');

        Route::get('education/result-checker', App\Livewire\Admin\Education\ResultChecker\Index::class)->name('admin.education.result-checker');
        Route::get('education/result-checker/vendor/{vendor:id}/exam/{exam:id}/edit', App\Livewire\Admin\Education\ResultChecker\Edit::class)->name('admin.education.result-checker.edit');
    
        Route::get('wallet/user/{user:username}', App\Livewire\Admin\Wallet\Index::class)->name('admin.wallet.history');
    });

    // Route::get('/system/dashboard', [DashboardController::class, 'home'])->name('system.index');
});

