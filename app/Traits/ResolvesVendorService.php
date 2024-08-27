<?php

namespace App\Traits;

use Exception;
use App\Models\Vendor;
use App\Models\VendorServiceMapping;
use App\Services\Vendor\VendorServiceFactory;

trait ResolvesVendorService
{
    /**
     * Resolve the appropriate vendor instance based on the utility type.
     *
     * @param string $utilityType
     * @return Vendor
     * @throws Exception
     */
    public static function resolveServiceClass(string $utilityType)
    {
        $vendorId = self::getActiveVendorId($utilityType);

        $vendor = Vendor::find($vendorId);

        return VendorServiceFactory::make($vendor);
    }

    public static function getVendorService(string $utilityType)
    {
        $vendorId = self::getActiveVendorId($utilityType);

        return Vendor::find($vendorId);
    }

    /**
     * Get the active vendor ID for the given utility type.
     *
     * @param string $utilityType
     * @return int
     * @throws Exception
     */
    protected static function getActiveVendorId(string $utilityType)
    {
        $mapping = VendorServiceMapping::where('service_type', $utilityType)->first();

        if (!$mapping) {
            throw new Exception("No active vendor found for utility type '{$utilityType}'.");
        }

        return $mapping->vendor_id;
    }
}
