<?php

namespace App\Livewire\Admin\Utility\Cable\Plan;

use Livewire\Component;
use App\Models\Utility\Cable;
use Livewire\Attributes\Rule;
use App\Models\Data\DataVendor;
use App\Models\Utility\CablePlan;

class Create extends Component
{
    public $vendor;
    public $cable;

    #[Rule('required|integer')]
    public $api_id;
    #[Rule('required|numeric')]
    public $amount;
    #[Rule('required|string')]
    public $package;
    #[Rule('required|boolean')]
    public $status;

    public function mount(Cable $cable, DataVendor $vendor)
    {
        $this->vendor = $vendor;
        $this->cable = $cable;
        $this->authorize('create cable utility');
    }

    public function store()
    {
        $this->validate();

        $checkIfApiIdExists = CablePlan::whereVendorId($this->vendor->id)->whereCableId($this->cable->cable_id)->whereCablePlanId($this->api_id)->count();
        if ($checkIfApiIdExists > 0) return $this->dispatch('error-toastr', ['message' => "API ID already exists on vendor({$this->vendor->name}). Please verify the API ID"]);
        

        CablePlan::create([
            'vendor_id'     =>  $this->vendor->id,
            'cable_id'      =>  $this->cable->cable_id,
            'cable_name'    =>  $this->cable->cable_name,
            'cable_plan_id' =>  $this->api_id,
            'package'       =>  $this->package,
            'amount'        =>  $this->amount,
            'status'        =>  $this->status
        ]);

        $this->dispatch('success-toastr', ['message' => 'Cable Plan Added Successfully']);
        session()->flash('success', 'Cable Plan Added Successfully');
        return redirect()->to(route('admin.utility.cable.plan', [$this->vendor->id, $this->cable->id]));
    }

    public function render()
    {
        return view('livewire.admin.utility.cable.plan.create');
    }
}
