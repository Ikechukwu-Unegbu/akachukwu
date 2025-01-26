<?php

namespace App\Traits;

use App\Models\AirtimeVendorMapping;
use App\Models\Data\DataNetwork;
use Exception;
use App\Models\Vendor;
use App\Models\VendorServiceMapping;
use App\Services\Vendor\VendorServiceFactory;

trait ResolvesAirtimeVendorService
{
    /**
     * Resolve the appropriate vendor instance based on the utility type.
     *
     * @param string $utilityType
     * @return Vendor
     */
    public static function resolveVendorServiceClass(int $vendorId, int $networkId)
    {
        return self::getVendorForAirtime($vendorId, $networkId);

        // $vendor = Vendor::find($vendorForAirtime->vendor_id);

        // return VendorServiceFactory::make($vendor);
    }

    /**
     * Get the active vendor ID for the given utility type.
     *
     * @param string $utilityType
     * @return int
     * @throws Exception
     */
    protected static function getVendorForAirtime(int $vendorId, int $networkId)
    {
        $network = DataNetwork::where('network_id', $networkId)->where('vendor_id', $vendorId)->first();
    
        if (!$network) {
            throw new Exception("No active network vendor found for airtime network ID: '{$networkId}'.");
        }

        $mapping = AirtimeVendorMapping::where('network', $network->name)->first();
    
        if (!$mapping) {
            throw new Exception("No vendor mapping found for airtime network: '{$network->name}'.");
        }
        
        $network = DataNetwork::where('name', $network->name)->where('vendor_id', $mapping->vendor_id)->first();

        if (!$network) {
            throw new Exception("No network found for airtime network: '{$network->name}'.");
        }

        return (object) [
            'vendor'     => Vendor::find($mapping->vendor_id),
            'networkId'  => $network->network_id,
        ];
    }
    
}
