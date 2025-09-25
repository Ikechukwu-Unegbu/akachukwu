<?php

namespace App\Services\Airtime;

use Carbon\Carbon;
use App\Models\Vendor;
use App\Helpers\ApiHelper;
use App\Models\SiteSetting;
use App\Traits\HandlesPostNoDebit;
use Illuminate\Support\Facades\Auth;
use App\Traits\ResolvesVendorService;
use App\Models\Utility\AirtimeTransaction;
use App\Traits\ResolvesAirtimeVendorService;
use App\Services\Vendor\VendorServiceFactory;

class AirtimeService
{
    use ResolvesVendorService, ResolvesAirtimeVendorService, HandlesPostNoDebit;

    public static function create($vendorId, $networkId, $amount, $mobileNumber, $isScheduled = false, $scheduledPayload = [], $isInitialRun = false, $hasTransaction = null, $wallet='base_wallet')
    {
        // $vendorService = (new self)->resolveServiceClass('airtime');
        if ( self::ensurePostNoDebitIsAllowed()) {
            return ApiHelper::sendError([], 'Your account is restricted from performing debit operations.', 403);
        }

        $checkLimit = self::checkAirtimeLimit($amount);
        if ($checkLimit !== true) {
            return $checkLimit;
        }

        $resolveVendorServiceClass = (new self)->resolveVendorServiceClass($vendorId, $networkId);
        $vendorService = VendorServiceFactory::make($resolveVendorServiceClass->vendor);
        return $vendorService::airtime($resolveVendorServiceClass->networkId, $amount, $mobileNumber, $isScheduled, $scheduledPayload, $isInitialRun, $hasTransaction, $wallet);
    }

    public static function checkAirtimeLimit($amount)
    {
        $settings = SiteSetting::first();

        if (!$settings || !$settings->airtime_sales) {
            return ApiHelper::sendError([], 'Airtime purchase is currently unavailable.');
        }

        $dailyLimit = $settings->airtime_limit;
        $userId = Auth::id();

        $totalSpentToday = AirtimeTransaction::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        if (($totalSpentToday + $amount) > $dailyLimit) {
            return ApiHelper::sendError([], "You have exceeded your daily airtime transaction limit of ₦{$dailyLimit}.");
        }

        return true;
    }
}
