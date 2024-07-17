<?php

namespace App\Services\Education;

use App\Models\Vendor;
use App\Models\Education\ResultChecker;
use App\Services\Vendor\VendorServiceFactory;
class ResultCheckerService
{   
    public static function create($vendorId, $exam, $quantity)
    {
        $vendor = Vendor::find($vendorId);
        $vendorFactoryService = VendorServiceFactory::make($vendor);
        return $vendorFactoryService::resultChecker($exam, $quantity);
    }

    public static function exams($vendorId)
    {
        return ResultChecker::whereVendorId($vendorId)->whereStatus(true)->get();
    }
}