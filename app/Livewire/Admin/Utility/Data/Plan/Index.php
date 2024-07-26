<?php

namespace App\Livewire\Admin\Utility\Data\Plan;

use App\Models\Data\DataNetwork;
use App\Models\Data\DataPlan;
use App\Models\Data\DataType;
use App\Models\Vendor;
use App\Services\Vendor\VendorServiceFactory;
use Livewire\Component;

class Index extends Component
{
    public $vendor;
    public $network;
    public $type;

    public function mount(DataType $type, Vendor $vendor, DataNetwork $network)
    {
        $this->authorize('view data utility');
        $this->vendor = $vendor;
        $this->network = $network;
        $this->type = $type;

        $vendorFactory = VendorServiceFactory::make($this->vendor);
        $vendorFactory::getDataPlans();
    }

    public function render()
    {
        return view('livewire.admin.utility.data.plan.index', [
            'dataPlans' => DataPlan::with('type')->whereVendorId($this->vendor->id)->whereNetworkId($this->network->network_id)->whereTypeId($this->type->id)->get()
        ]);
    }
}
