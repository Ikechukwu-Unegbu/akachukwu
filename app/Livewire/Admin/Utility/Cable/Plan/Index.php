<?php

namespace App\Livewire\Admin\Utility\Cable\Plan;

use App\Models\Vendor;
use Livewire\Component;
use App\Models\Utility\Cable;
use App\Models\Utility\CablePlan;
use App\Services\Vendor\VendorServiceFactory;

class Index extends Component
{
    public $cable;
    public $vendor;

    public function mount(Cable $cable, Vendor $vendor)
    {
        $this->vendor = $vendor;
        $this->cable = $cable;
        $this->authorize('view cable utility');

        $vendorFactory = VendorServiceFactory::make($this->vendor);
        $vendorFactory::getCablePlans($this->cable->id);
    }

    public function render()
    {
        return view('livewire.admin.utility.cable.plan.index', [
            'cablePlans'    =>  CablePlan::with('cable')->whereVendorId($this->vendor->id)->whereCableId($this->cable->cable_id)->get()
        ]);
    }
}
