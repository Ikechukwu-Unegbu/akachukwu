<?php

namespace App\Services\Cable;

use App\Models\Vendor;
use App\Traits\ResolvesVendorService;
use App\Services\Vendor\VendorServiceFactory;

class CableService
{
    use ResolvesVendorService;
    
    public static function create($vendorId, $cableId, $cablePlan, $iucNumber, $customer)
    {
        $vendorService = (new self)->resolveServiceClass('cable');
        return $vendorService::cable($cableId, $cablePlan, $iucNumber, $customer);
    }

    public static function validateIUCNumber($vendorId, $iucNumber, $cableName)
    {
        $vendorService = (new self)->resolveServiceClass('cable');
        return $vendorService::validateIUCNumber($iucNumber, $cableName);
    }
}
