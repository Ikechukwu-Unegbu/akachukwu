<?php

namespace App\Services;
use App\Models\Vendor;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class VendorTestService
{
    protected $vendorConfig;
    protected $vendor;

    public function __construct($vendor = 'VTPASS')
    {
        $vendors = config('vendortest');
        $queryVendor = Vendor::query();
        if (isset($vendors[$vendor]) && $queryVendor->where('name', $vendor)->exists()) {
            $this->vendorConfig = $vendors[$vendor];
            $this->vendor = $queryVendor->where('name', $vendor)->first();
        } else {
            throw new \Exception("Vendor configuration not found for {$vendor}");
        }
    }

    public function getVendor()
    {
        return $this->vendor;
    }

    public function getPhoneNumber()
    {
        return $this->vendorConfig['phone_number'];
    }

    public function getNetworkId()
    {
        return $this->vendorConfig['network_id'];
    }

    public function getDataId()
    {
        return $this->vendorConfig['data_id'];
    }

    public function getDataTypeId()
    {
        return $this->vendorConfig['datatype_id'];
    }

    public function getPlanId()
    {
        return $this->vendorConfig['plan_id'];
    }

    public function getAmount()
    {
        return $this->vendorConfig['amount'];
    }

    public function getMeterNumber()
    {
        return $this->vendorConfig['meter_number'];
    }

    public function getDiscoId()
    {
        return $this->vendorConfig['disco_id'];
    }

    public function getMeterType()
    {
        return $this->vendorConfig['meter_type'];
    }
}