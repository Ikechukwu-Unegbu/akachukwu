<?php

namespace App\Http\Controllers\V1\API\Auth;

use Throwable;
use App\Models\User;
use App\Services\OTPService;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Throw_;
use App\Notifications\WelcomeEmail;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\LoginNotification;
use App\Repositories\UserDeviceRepository;
use App\Services\V1\User\UserProfileService;
use Illuminate\Support\Facades\Notification;
use App\Services\OneSignalNotificationService;
use App\Actions\Automatic\Accounts\GenerateRemainingAccounts;

class AuthenticateUserController extends Controller
{
    public $userDeviceRepository;

    public function __construct(UserDeviceRepository $userDeviceRepository)
    {
        $this->userDeviceRepository = $userDeviceRepository;
    }

    public function login(Request $request, UserProfileService $service)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ], [
            'login.required' => 'Username or email field is empty',
            'password.required' => 'Password field is required',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = $service->getUser($request->login);
        if($user) {
            $blockedResponse = $this->ensureUserIsNotBlockedOrBlacklisted($user);
            if ($blockedResponse) return $blockedResponse;
        }

        if (Auth::attempt([$loginType => $request->login, 'password' => $request->password])) {
            $user = User::find(Auth::user()->id);
            $user->load('virtualAccounts');
            $token = $request->user()->createToken('token-name')->plainTextToken;

            (new GenerateRemainingAccounts)->generateRemaingingAccounts();

            if ($request->os_player_id) {
                $this->userDeviceRepository->updateOrCreate($user, ['os_player_id' => $request->os_player_id]);
            }

            try {
                $user->notify(new LoginNotification($user->username));
            } catch (Throwable $th) {
                Log::error('Failed to login notification: ' . $th->getMessage());
            }

            return response()->json([
                'token' => $token,
                'status' => 'success',
                'user' => $user
            ], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }


    public function verifyOtp(Request $request, OTPService $otpService)
    {
        $request->validate([
            'otp'=>'required|string',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class, 
            function ($attribute, $value, $fail) {
                // Top 5 popular providers (you can tweak this list)
                $allowedDomains = [
                    'gmail.com',
                    'yahoo.com',
                    'outlook.com',
                    'hotmail.com',
                    'icloud.com',
                ];

                $domain = strtolower(substr(strrchr($value, "@"), 1));

                if (!in_array($domain, $allowedDomains)) {
                    $fail("The $attribute must be from a supported provider (Gmail, Yahoo, Outlook/Hotmail, iCloud).");
                }
            },]
        ]);

        $user = User::where('email', $request->email)->first();
        $checkOtp = $otpService->verifyOTP($user, $request->otp);

        if($checkOtp == true){
            return response()->json([
                'status'=>'success',
                'message'=>'Valid Otp'
            ], 200);
        }
        return response()->json([
            'status'=>'failed',
            'message'=>'Invalid otp'
        ], 403);
    }

    public function resendOtp(Request $request, OTPService $otpService)
    {
        $request->validate([
            'email'=>'required|email'
        ]);
        $user =  User::where('email', $request->email)->first();

        $otp = $otpService->generateOTP($user);
        Notification::sendNow($user, new WelcomeEmail($otp, $user));
        return response()->json([
            'status'=>'success',
            'New OTP sent'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    private function ensureUserIsNotBlockedOrBlacklisted($user)
    {
        if ($user->blocked_by_admin) {
            return response()->json(['message' => 'Account Currently Inaccessible. Please contact Support for further assistance.'], 403);
        }
        if ($user->is_blacklisted) {
            return response()->json(['message' => 'Account Currently Blacklisted. Please contact Support for further assistance.'], 403);
        }
        return null;
    }
}
