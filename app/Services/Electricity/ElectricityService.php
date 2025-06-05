<?php

namespace App\Services\Electricity;

use App\Models\Vendor;
use App\Helpers\ApiHelper;
use App\Traits\HandlesPostNoDebit;
use App\Traits\ResolvesVendorService;
use App\Services\Vendor\VendorServiceFactory;

class ElectricityService
{
    use ResolvesVendorService, HandlesPostNoDebit;

    public static function create($vendorId, $discoId, $meterNumber, $meterType, $amount, $customerName, $customerMobile, $customerAddress)
    {
        if ( self::ensurePostNoDebitIsAllowed()) {
            return ApiHelper::sendError([], 'Your account is restricted from performing debit operations.', 403);
        }

        $vendorService = (new self)->resolveServiceClass('electricity');
        return $vendorService::electricity($discoId, $meterNumber, $meterType, $amount, $customerName, $customerMobile, $customerAddress);
    }

    public static function validateMeterNumber($vendorId, $meterNumber, $discoId, $meterType)
    {
        $vendorService = (new self)->resolveServiceClass('electricity');
        return $vendorService::validateMeterNumber($meterNumber, $discoId, $meterType);
    }
}
