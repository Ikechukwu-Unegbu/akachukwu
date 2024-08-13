<?php

namespace App\Livewire\Admin\Api\Vendor;

use App\Models\Vendor;
use App\Services\Vendor\VendorService;
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
        $this->balance = VendorService::getVendorAccountBalance($vendor);
        $this->authorize('view vendor api');
    }

  

    public function render()
    {
        return view('livewire.admin.api.vendor.show');
    }
}
