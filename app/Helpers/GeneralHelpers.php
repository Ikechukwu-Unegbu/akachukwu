<?php 
namespace App\Helpers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Referral;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GeneralHelpers{
        /**
     * Generate a unique UUID for the given table.
     *
     * @param string $table
     * @return string
     */
    public static function generateUniqueUuid(string $table): string
    {
        $uuid = Str::uuid();

        while (DB::table($table)->where('uuid', $uuid)->exists()) {
            $uuid = Str::uuid();
        }

        return $uuid;
    }
    public static function generateUniqueRef(string $table): string
    {
        $uuid = Str::uuid();

        while (DB::table($table)->where('reference_id', $uuid)->exists()) {
            $uuid = Str::uuid();
        }

        return $uuid;
    }

    public static function generateUniqueNumericRef(string $table): string
    {
        do {
            // Generate a 12-digit random number with leading zeroes if necessary
            $numericRef = str_pad((string)random_int(0, 999999999999), 12, '0', STR_PAD_LEFT);
        } while (DB::table($table)->where('reference_id', $numericRef)->exists());

        return $numericRef;
    }
     /**
     * Determine if the given string is an email address or a username.
     *
     * @param string $input
     * @return string
     */
    public  function identifyStringType(string $input): string
    {
        // Define a simple email validation rule
        $emailRule = 'email';

        // Validate if the input is an email
        $validator = Validator::make(['input' => $input], [
            'input' => $emailRule,
        ]);

        if ($validator->passes()) {
            return 'Email';
        }

        return 'Username';
    }


    public static function checkReferrer($request, $user)
    {
        $existingReferral = Referral::where('referred_email', $user->email)
                                    ->where('status', '!=', 'completed')
                                    ->first();

        if ($existingReferral) {
            $existingReferral->update([
                'referred_user_id' => $user->id,
                'status' => 'completed',
            ]);
        } else {
            if ($request->filled('referral_code')) {
                $referrer = User::where('username', $request->referral_code)->first();

                if ($referrer) {
                    Referral::create([
                        'referrer_id' => $referrer->id,
                        'referred_user_id' => $user->id,
                        'referred_email' => $user->email,
                        'status' => 'completed',
                    ]);
                }
            }
        }
    }

    public static function randomDelay($seconds = 10) : void
    {
        $delay = rand(1, $seconds);
        sleep($delay);
    }

    public static function minimumTransaction($amount) : bool
    {
        $siteSetting = SiteSetting::find(1);
        if ($siteSetting && $siteSetting->minimum_transfer > $amount) {
            return false;
        }
        return true;
    }

    public static function dailyTransactionLimit($model, $amount, $userId)
    {
        $settings = SiteSetting::first();
        $dailyLimit = $settings->maximum_transfer;

        $totalSpentToday = $model::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        return ($totalSpentToday + $amount) <= $dailyLimit;
    }

    public static function calculateWalletFunding($fundedAmount)
    {
        $siteSettings = SiteSetting::first();
        
        $chargePercentage = $siteSettings->card_charges ?? 0.0;
    
        $serviceCharge = ($fundedAmount * $chargePercentage) / 100;
    
        $totalAmountToPay = $fundedAmount + $serviceCharge;
    
        return self::customRoundDown($totalAmountToPay);
    }

    public static function customRoundDown(float $number): float
    {
        return floor($number * 100) / 100;
    }
    
    public static function calculateWithCharge($amount) 
    {
        $siteSettings = SiteSetting::first();        
        $chargePercentage = $siteSettings->card_charges ?? 0.0;
    
        $serviceCharge = ($amount * $chargePercentage) / 100;
        $totalAmount = $amount + $serviceCharge;
        
        return self::customRoundDown($totalAmount);
    }

    public static function sendOneSignalTransactionNotification($service, $message, $amount, string $notificationClass): bool
    {
        try {
            $user = User::find(Auth::id());

            if (!$user) {
                Log::error('Failed to send notification: User not found');
                return false;
            }

            if (!class_exists($notificationClass)) {
                Log::error("Notification class {$notificationClass} does not exist");
                return false;
            }

            $status = $service->response->status ?? false;

            $user->notify(new $notificationClass($status, $message, $amount));
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send notification: ' . $e->getMessage());
            return false;
        }
    }

    public static function totalUsersCount()
    {
        return User::count();
    }
}
