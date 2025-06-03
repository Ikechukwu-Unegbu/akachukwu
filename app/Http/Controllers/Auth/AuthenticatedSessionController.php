<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Automatic\Accounts\GenerateRemainingAccounts;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\V1\User\UserProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Services\Admin\Activity\ActivityLogService;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    public function new_create(): View
    {
        return view('auth.new-login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request, UserProfileService $service, GenerateRemainingAccounts $accountsGenerator): RedirectResponse
    {
        $user = $service->getUser($request->username);
        if($user){
            if ($user->blocked_by_admin == true) {
                $validator = Validator::make([], []);
                $validator->errors()->add('blocked', 'Account Currently Inaccessible. Please contact Support for further assistance.');
                throw new ValidationException($validator);
                return false;
            }
        }
        $request->authenticate();
       
        //log action
        ActivityLogService::log([
            'activity'=>"Login",
            'description'=>'Login',
            'type'=>'auth',
            // 'balance_before'=>$user->account_balance,
            // 'balance_after'=>$user->account_balance, 
        ]);
        
        $request->session()->regenerate();

        $accountsGenerator->generateRemaingingAccounts();

        return redirect()->to(Auth::user()->dashboard());
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
