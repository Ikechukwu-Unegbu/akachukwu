<?php

namespace App\Services\Electricity;

use App\Models\Vendor;
use App\Traits\ResolvesVendorService;
use App\Services\Vendor\VendorServiceFactory;

class ElectricityService 
{
    use ResolvesVendorService;
    
    public static function create($vendorId, $discoId, $meterNumber, $meterType, $amount, $customerName, $customerMobile, $customerAddress)
    {
        $vendorService = (new self)->resolveServiceClass('electricity');
        return $vendorService::electricity($discoId, $meterNumber, $meterType, $amount, $customerName, $customerMobile, $customerAddress);
    }

    public static function validateMeterNumber($vendorId, $meterNumber, $discoId, $meterType) 
    {
        $vendorService = (new self)->resolveServiceClass('electricity');
        return $vendorService::validateMeterNumber($meterNumber, $discoId, $meterType);
    }
}