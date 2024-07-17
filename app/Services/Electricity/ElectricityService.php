<?php

namespace App\Services\Electricity;

use App\Models\Vendor;
use App\Services\Vendor\VendorServiceFactory;
class ElectricityService 
{
    public static function create($vendorId, $discoId, $meterNumber, $meterType, $amount, $customerName, $customerMobile, $customerAddress)
    {
        $vendor = Vendor::find($vendorId);
        $vendorFactoryService = VendorServiceFactory::make($vendor);
        return $vendorFactoryService::electricity($discoId, $meterNumber, $meterType, $amount, $customerName, $customerMobile, $customerAddress);
    }

    public static function validateMeterNumber($vendorId, $meterNumber, $discoId, $meterType) 
    {
        $vendor = Vendor::find($vendorId);
        $vendorFactoryService = VendorServiceFactory::make($vendor);
        return $vendorFactoryService::validateMeterNumber($meterNumber, $discoId, $meterType);
    }
}