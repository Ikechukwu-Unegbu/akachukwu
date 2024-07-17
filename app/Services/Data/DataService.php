<?php

namespace App\Services\Data;

use App\Models\Vendor;
use App\Services\Vendor\VendorServiceFactory;

class DataService 
{
    public static function create($vendorId, $networkId, $typeId, $dataId, $mobileNumber)
    {
        $vendor = Vendor::find($vendorId);
        $vendorFactoryService = VendorServiceFactory::make($vendor);
        return $vendorFactoryService::data($networkId, $typeId, $dataId, $mobileNumber);
    }
}