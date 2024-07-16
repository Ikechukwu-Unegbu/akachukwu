<?php

namespace App\Livewire\Admin\Api\Vendor;

use App\Models\Vendor;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Services\Vendor\VendorServiceFactory;

class Show extends Component
{
    public $vendor;
    public $balance;

    public function mount(Vendor $vendor)
    {
        $this->vendor = $vendor;
        $this->balance = $this->getVendorAccountBalance();
        $this->authorize('view vendor api');
    }

    public function getVendorAccountBalance()
    {
        $vendorWallet =  VendorServiceFactory::make($this->vendor);

        $balance =  $vendorWallet::getWalletBalance();

        return ($balance->status) ? $balance->response : 'N/A';
    }

    public function render()
    {
        return view('livewire.admin.api.vendor.show');
    }
}
