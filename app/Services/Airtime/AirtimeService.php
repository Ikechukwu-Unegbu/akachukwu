<?php

namespace App\Services\Airtime;

use App\Models\Vendor;
use App\Traits\ResolvesVendorService;
use App\Services\Vendor\VendorServiceFactory;
use App\Traits\ResolvesAirtimeVendorService;

class AirtimeService 
{
    use ResolvesVendorService, ResolvesAirtimeVendorService;

    public static function create($vendorId, $networkId, $amount, $mobileNumber)
    {
        // $vendorService = (new self)->resolveServiceClass('airtime');
        $resolveVendorServiceClass = (new self)->resolveVendorServiceClass($vendorId, $networkId);    
        $vendorService = VendorServiceFactory::make($resolveVendorServiceClass->vendor);        
        return $vendorService::airtime($resolveVendorServiceClass->networkId, $amount, $mobileNumber);
    }
}