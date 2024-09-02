<?php

namespace App\Services\Airtime;

use App\Models\Vendor;
use App\Traits\ResolvesVendorService;
use App\Services\Vendor\VendorServiceFactory;

class AirtimeService 
{
    use ResolvesVendorService;

    public static function create($vendorId, $networkId, $amount, $mobileNumber)
    {
        $vendorService = (new self)->resolveServiceClass('airtime');
        return $vendorService::airtime($networkId, $amount, $mobileNumber);
    }
}