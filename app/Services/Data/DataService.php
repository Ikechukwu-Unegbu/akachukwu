<?php

namespace App\Services\Data;

use App\Models\Vendor;
use App\Traits\ResolvesVendorService;
use App\Services\Vendor\VendorServiceFactory;

class DataService 
{
    use ResolvesVendorService;
    
    public static function create($vendorId, $networkId, $typeId, $dataId, $mobileNumber)
    {
        $vendorService = (new self)->resolveServiceClass('data');
        return $vendorService::data($networkId, $typeId, $dataId, $mobileNumber);
    }
}