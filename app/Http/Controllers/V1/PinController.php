<?php

namespace App\Http\Controllers\V1;

use App\Models\Otp;
use App\Models\User;
use App\Services\OTPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Account\UserPinService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PinSetupOTPNotification;

class PinController extends Controller
{

    protected $otpService;

    public function __construct(OTPService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function sendOtp()
    {
        try {
            $user = Auth::user();
            $type = !empty($user->pin) ? 'pin_reset' : 'pin_setup';
            $otp = $this->otpService->generateOTP($user, $type);
            
            Notification::sendNow($user, new PinSetupOTPNotification( $user, $otp,$type));
            return response()->json([
                'status' => true,
                'message' => 'OTP sent successfully!',
            ], 200);
        } catch (\Throwable $th) {
            Log::error('OTP generation failed for user ID ' . ($user->id ?? 'unknown') . ': ' . $th->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to send OTP. Please try again later.',
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:4',
        ]);

        $user = Auth::user();
        $type = !empty($user->pin) ? 'pin_reset' : 'pin_setup';
        $verifyOtp = $this->otpService->verifyOTP($user, $request->otp, $type);
        
        if ($verifyOtp == false) {
            return redirect()->back()->withErrors( 'The OTP you entered is incorrect. Please try again.');
        }

        session()->flash('success', 'OTP verified successfully!');
        
        $url = url()->previous() . '?otp_verified=true';
        return redirect($url);
    }

    public function update(Request $request)
    {        
        $validator = Validator::make($request->all(), [
            'new_pin' => 'required|string|size:4',
            'confirm_pin' => 'required|string|size:4|same:new_pin',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $user = Auth::user();
        $type = !empty($user->pin) ? 'pin_reset' : 'pin_setup';
        if (Otp::where('user_id', $user->id)->where('type', $type)->exists()) {
            return redirect()->route('settings.credentials')->withErrors( 'Complete OTP verification to set up or reset your PIN. Check your email or request a new OTP.');
        }

        UserPinService::updatePin($user, $request->new_pin);
        $message = sprintf("Your PIN has been %s successfully.", !empty($user->pin) ? 'updated' : 'created');
        session()->flash('success', $message);
        return redirect()->route('settings.credentials');
    }
}
