<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use App\Services\OTPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Account\UserPinService;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PinSetupOTPNotification;
use Illuminate\Contracts\Auth\Authenticatable;

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
            $otp = $this->otpService->generateOTP($user, 'pin_reset');
            $purpose = !empty($user->pin) ? 'pin_reset' : 'Setup PIN';
            
            Notification::sendNow($user, new PinSetupOTPNotification( $user, $otp,$purpose));
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

        $verifyOtp = $this->otpService->verifyOTP($user, $request->otp, 'pin_reset');
        
        if ($verifyOtp == false) {
            return redirect()->back()->withErrors( 'The OTP you entered is incorrect. Please try again.');
        }

        session()->flash('success', 'OTP verified successfully!');
        
        $url = url()->previous() . '?otp_verified=true';
        return redirect($url);
    }

    public function update(Request $request)
    {
        
    }
}
