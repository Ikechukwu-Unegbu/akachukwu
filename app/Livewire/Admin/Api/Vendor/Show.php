<?php

namespace App\Livewire\Admin\Api\Vendor;

use App\Models\Vendor;
use App\Services\Vendor\VendorService;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Services\Vendor\VendorServiceFactory;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\Admin\Activity\ActivityLogService;


class Show extends Component
{
    public $vendor;
    public $balance;

    public function mount(Vendor $vendor)
    {
        $this->authorize('view vendor api');

        $this->vendor = $vendor;
        $this->balance = VendorService::getVendorAccountBalance($vendor);

        ActivityLogService::log([
            'activity'=>"View",
            'description'=>'Viewing Vendor Single Vendor: '.$this->vendor->name,
            'type'=>'Vendors',
            'tags'=>['View', 'Vendors']
        ]);
        
    }

  

    public function render()
    {
        return view('livewire.admin.api.vendor.show');
    }
}
