<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use App\Services\Payment\MonnifyService;
use App\Services\Payment\VirtualAccountServiceFactory;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    public function new_create()
    {
        return view('auth.new-register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:255', 'alpha_dash', 'unique:'.User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults(),         function ($attribute, $value, $fail) {
                if (!preg_match('/[A-Z]/', $value)) {
                    $fail('The '.$attribute.' must contain at least one uppercase letter.');
                }
                if (!preg_match('/[a-z]/', $value)) {
                    $fail('The '.$attribute.' must contain at least one lowercase letter.');
                }
                if (!preg_match('/[0-9]/', $value)) {
                    $fail('The '.$attribute.' must contain at least one number.');
                }
            }
    ],
            'terms_and_conditions'=>['required'],
            'phone_number'  =>  ['required', 'regex:/^0(70|80|81|90|91|80|81|70)\d{8}$/']
        ]);

        try {
            DB::transaction(function () use($request) {
                $user = User::create([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role'  =>  'user',
                    'phone' => $request->phone_number
                ]);
        
                // MonnifyService::createVirtualAccount($user);
                $activeGateway = PaymentGateway::where('va_status', true)->first();
                $virtualAccountFactory = VirtualAccountServiceFactory::make($activeGateway);
                $virtualAccountFactory::createVirtualAccount($user);

                event(new Registered($user));
            });
            session()->flash('success', 'Your account has been created successfully. Please proceed to login.');
            return redirect(route('login'));
        } catch (\Throwable $th) {
            
        }
        
    }
}
