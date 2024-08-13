<?php 
namespace App\Services\Vendor;

use App\Models\Vendor;

class VendorService{

    public static function getVendorAccountBalance(Vendor $vendor)
    {
        $vendorWallet =  VendorServiceFactory::make($vendor);

        $balance =  $vendorWallet::getWalletBalance();

        return ($balance->status) ? $balance->response : 'N/A';
    }

    
}