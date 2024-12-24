<?php 
namespace App\Services;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;


class OTPService{
    
    protected $otpExpirationMinutes = 10; // OTP validity duration in minutes

    /**
     * Generate and store an OTP for a given user.
     *
     * @param User $user
     * @return string The generated OTP
     */
    public function generateOTP(User $user, $type = 'registration')
    {
        $otp = mt_rand(1000, 9999);

        // Create or update the OTP record
        OTP::updateOrCreate(
            [
                'user_id' => $user->id,
                'type'  =>  $type
            ],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes($this->otpExpirationMinutes)
            ]
        );

        return $otp;
    }

    /**
     * Verify the OTP for a given user.
     *
     * @param User $user
     * @param string $otp
     * @return bool True if OTP is valid, false otherwise
     */
    public function verifyOTP(User $user, $otp, $type = 'registration')
    {
       
        $otpRecord = Otp::where('user_id', $user->id)
                        ->where('otp', $otp)
                        ->where('type', $type)
                        ->where('expires_at', '>', Carbon::now())
                        ->first();

        if ($otpRecord) {
            $otpRecord->delete();
            return true;
        }

        return false;
    }

    
}