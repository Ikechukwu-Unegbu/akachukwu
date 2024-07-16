<?php

namespace App\Services\Cable;

use App\Models\Vendor;
use App\Services\Vendor\VendorServiceFactory;
class CableService
{
    public static function create($vendorId, $cableId, $cablePlan, $iucNumber, $customer)
    {
        $vendor = Vendor::find($vendorId);
        $vendorFactoryService = VendorServiceFactory::make($vendor);
        return $vendorFactoryService::cable($cableId, $cablePlan, $iucNumber, $customer);
    }

    public static function validateIUCNumber($vendorId, $iucNumber, $cableName)
    {
        $vendor = Vendor::find($vendorId);
        $vendorFactoryService = VendorServiceFactory::make($vendor);
        return $vendorFactoryService::validateIUCNumber($iucNumber, $cableName);
    }
}
