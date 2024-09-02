<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountDeletionOTP;
use App\Services\OTPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class AccountManagementContorller extends Controller
{

    protected $otpService;
    public function __construct(OTPService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function initiateAccountDeletion()
    {
       
        $user = User::find(Auth::user()->id);
        $otp = $this->otpService->generateOTP($user);
        Notification::sendNow($user, new AccountDeletionOTP($user, $otp));


        return ApiHelper::sendResponse([], 'Check your email for OTP');
    }

    public function confirmAccountDeletion(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'otp'=>'required|string'
        ]);

        if($validation->fails()){
            return ApiHelper::sendError($validation->errors(), 'Failed to delete account');
        }

        $verifyOtp = $this->otpService->verifyOTP(Auth::user(), $request->otp);
        if($verifyOtp == false){
            return ApiHelper::sendError(['Invalid OTP'], 'Invalid otp');
        }

        $user = User::find(Auth::user()->id);
        $request->user()->currentAccessToken()->delete();
        $user->delete();

        return ApiHelper::sendResponse([], 'Account deleted');
    }

}
