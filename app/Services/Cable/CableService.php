<?php

namespace App\Services\Cable;

use App\Models\Vendor;
use App\Helpers\ApiHelper;
use App\Traits\HandlesPostNoDebit;
use App\Traits\ResolvesVendorService;
use App\Services\Vendor\VendorServiceFactory;

class CableService
{
    use ResolvesVendorService, HandlesPostNoDebit;

    public static function create($vendorId, $cableId, $cablePlan, $iucNumber, $customer)
    {
        if ( self::ensurePostNoDebitIsAllowed()) {
            return ApiHelper::sendError([], 'Your account is restricted from performing debit operations.', 403);
        }

        $vendorService = (new self)->resolveServiceClass('cable');
        return $vendorService::cable($cableId, $cablePlan, $iucNumber, $customer);
    }

    public static function validateIUCNumber($vendorId, $iucNumber, $cableName)
    {
        $vendorService = (new self)->resolveServiceClass('cable');
        return $vendorService::validateIUCNumber($iucNumber, $cableName);
    }
}
