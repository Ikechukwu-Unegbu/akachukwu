<?php

namespace App\Livewire\Admin\Utility\Cable\Plan;

use App\Models\Data\DataVendor;
use App\Models\Utility\Cable;
use App\Models\Utility\CablePlan;
use Livewire\Component;

class Index extends Component
{
    public $cable;
    public $vendor;

    public function mount(Cable $cable, DataVendor $vendor)
    {
        $this->vendor = $vendor;
        $this->cable = $cable;
    }

    public function render()
    {
        return view('livewire.admin.utility.cable.plan.index', [
            'cablePlans'    =>  CablePlan::with('cable')->whereVendorId($this->vendor->id)->whereCableId($this->cable->cable_id)->get()
        ]);
    }
}
