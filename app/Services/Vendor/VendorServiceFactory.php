<?php

namespace App\Services\Vendor;

use App\Models\Vendor;
use App\Services\Vendor\VTPassService;
use App\Services\Vendor\GladTidingService;
use App\Services\Vendor\PosTraNetService;

class VendorServiceFactory
{
    public static function make(Vendor $vendor)
    {
        switch ($vendor->name) {
            case 'GLADTIDINGSDATA':
                return (new GladTidingService($vendor))::class;
            case 'POSTRANET':
                return (new PosTraNetService($vendor))::class;
            case 'VTPASS':
                return (new VTPassService($vendor))::class;
            default:
                throw new \InvalidArgumentException("Unsupported vendor");
        }
    }
}
