<?php

namespace App\Services;
use Carbon\Carbon;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Auth;
use App\Models\Utility\AirtimeTransaction;

class AirtimeValidationService
{
    /**
     * Check if airtime purchase is allowed
     *
     * @return bool
     */
    public static function isAirtimePurchaseAllowed() : bool
    {
        $settings = SiteSetting::find(1);
        return $settings && $settings->airtime_sales;
    }

    /**
     * Check if airtime purchase limit
     *
     * @return bool
     */
    public static function canPurchaseAirtime($amount): bool
    {
        $userId = Auth::id();
        $settings = SiteSetting::find(1);
        $dailyLimit = $settings->airtime_limit;

        $totalSpentToday = AirtimeTransaction::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        return ($totalSpentToday + $amount) <= $dailyLimit;
    }

     /**
     * Validate airtime amount
     *
     * @param float $amount
     * @return string|null
     */
    public static function validateAirtimeAmount($amount): ?string
    {
        if (!self::isAirtimePurchaseAllowed()) {
            return 'Airtime purchase is currently unavailable.';
        }

        if (!self::canPurchaseAirtime($amount)) {
            return "You have reached your daily airtime transaction limit ₦{$amount}.";
        }

        return null;
    }
}