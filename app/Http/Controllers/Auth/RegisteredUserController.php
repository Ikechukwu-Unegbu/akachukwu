<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use App\Services\OTPService;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;
use App\Helpers\GeneralHelpers;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use App\Notifications\WelcomeEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use App\Services\Payment\MonnifyService;
use Illuminate\Support\Facades\Notification;
use App\Services\Payment\VirtualAccountServiceFactory;
use App\Services\Payment\Crypto\WalletService as CryptoWalletService;
use App\Services\Payment\Crypto\QuidaxxService;

use App\Services\BranchReferralService;
class RegisteredUserController extends Controller
{

    public function __construct(
        protected OTPService $otpService
    ) {}

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
    public function store(Request $request)
    {
        $request->validate([
            // 'first_name' => ['required', 'string', 'max:255'],
            // 'last_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:255', 'alpha_dash', 'unique:' . User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => [
                'required',
                Rules\Password::defaults(),
                function ($attribute, $value, $fail) {
                    if (!preg_match('/[A-Z]/', $value)) {
                        $fail('The ' . $attribute . ' must contain at least one uppercase letter.');
                    }
                    if (!preg_match('/[a-z]/', $value)) {
                        $fail('The ' . $attribute . ' must contain at least one lowercase letter.');
                    }
                    if (!preg_match('/[0-9]/', $value)) {
                        $fail('The ' . $attribute . ' must contain at least one number.');
                    }
                }
            ],
            'terms_and_conditions' => ['required'],
            'phone_number'  =>  ['required', 'regex:/^0(70|80|81|90|91|80|81|70)\d{8}$/'],
            'referral_code' => ['nullable', 'string']
        ]);

        if ($request->filled('honey_field')) {
            return back()->withErrors(['error' => 'Bot detected']);
        }

        return DB::transaction(function () use ($request) {
            $user = User::create([
                // 'name' => $request->first_name . ' ' . $request->last_name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role'  =>  'user',
                'phone' => $request->phone_number
            ]);

            $otp = $this->otpService->generateOTP($user);

            GeneralHelpers::checkReferrer($request, $user);

            Notification::sendNow($user, new WelcomeEmail($otp, $user));

            // MonnifyService::createVirtualAccount($user);
            // $activeGateway = PaymentGateway::where('va_status', true)->first();
            // $virtualAccountFactory = VirtualAccountServiceFactory::make($activeGateway);
            // $virtualAccountFactory::createVirtualAccount($user);
            $referralService = new BranchReferralService();
            $referralLink = $referralService->createReferralLink($user->username);

            if ($referralLink) {
                $user->update(['referral_link' => $referralLink]);
            }


            event(new Registered($user));

            Auth::login($user);

            // Provision Quidax user and ensure NGN wallet (non-blocking to user flow)
            try {
                $cryptoWalletService = new CryptoWalletService();
                // Create Quidax user if missing
                if (empty($user->quidax_id)) {
                    $cryptoWalletService->createUser();
                    $user->refresh();
                }
                // Ensure NGN wallet is initialized/available
                $quidaxService = new QuidaxxService();
                $quidaxService->getUserWalletsCurrencyAddress('NGN');
            } catch (\Throwable $th) {
                // Log silently; do not interrupt registration flow
                \Log::warning('Quidax post-registration provisioning failed', ['error' => $th->getMessage()]);
            }

            session()->flash('success', 'Your account has been created successfully. Please proceed to login.');
            return redirect($user->dashboard());
        });
    }
}
