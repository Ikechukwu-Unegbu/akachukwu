<?php

namespace App\Livewire\Admin\Utility\Data\Plan;

use App\Models\Data\DataNetwork;
use App\Models\Data\DataPlan;
use App\Models\Data\DataType;
use App\Models\Data\DataVendor;
use Livewire\Component;

class Index extends Component
{
    public $vendor;
    public $network;
    public $type;

    public function mount(DataType $type, DataVendor $vendor, DataNetwork $network)
    {
        $this->vendor = $vendor;
        $this->network = $network;
        $this->type = $type;
    }

    public function render()
    {
        return view('livewire.admin.utility.data.plan.index', [
            'dataPlans' => DataPlan::with('type')->whereVendorId($this->vendor->id)->whereNetworkId($this->network->network_id)->whereTypeId($this->type->id)->get()
        ]);
    }
}
