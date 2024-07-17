<?php

namespace App\Services\Airtime;

use App\Models\Vendor;
use App\Services\Vendor\VendorServiceFactory;
class AirtimeService 
{
    public static function create($vendorId, $networkId, $amount, $mobileNumber)
    {
        $vendor = Vendor::find($vendorId);
        $vendorFactoryService = VendorServiceFactory::make($vendor);
        return $vendorFactoryService::airtime($networkId, $amount, $mobileNumber);
    }
}