<?php

namespace App\Livewire\Admin\Utility\Cable\Plan;

use Livewire\Component;
use App\Models\Utility\Cable;
use Livewire\Attributes\Rule;
use App\Models\Data\DataVendor;
use App\Models\Utility\CablePlan;

class Edit extends Component
{
    public $vendor;
    public $cable;
    public $plan;
    
    #[Rule('required|integer')]
    public $api_id;
    #[Rule('required|numeric')]
    public $amount;
    #[Rule('required|string')]
    public $package;
    #[Rule('required|boolean')]
    public $status;


    public function mount(Cable $cable, CablePlan $plan, DataVendor $vendor)
    {
        $this->vendor = $vendor;
        $this->cable = $cable;
        $this->plan = $plan;

        $this->api_id = $this->plan->cable_plan_id;
        $this->package = $this->plan->package;
        $this->amount = $this->plan->amount;
        $this->status = $this->plan->status ? true : false;
    }

    public function update()
    {
        $this->validate();

        if ($this->api_id !== $this->cable->cable_plan_id) {
            $checkIfApiIdExists = CablePlan::whereVendorId($this->vendor->id)->whereCableId($this->cable->cable_id)->whereCablePlanId($this->api_id)->where('id', '!=', $this->plan->id)->count();
            if ($checkIfApiIdExists > 0) return $this->dispatch('error-toastr', ['message' => "API ID already exists on vendor({$this->vendor->name}). Please verify the API ID"]);
        }

        $this->plan->update([
            'cable_plan_id' =>  $this->api_id,
            'package'       =>  $this->package,
            'amount'        =>  $this->amount,
            'status'        =>  $this->status
        ]);

        $this->dispatch('success-toastr', ['message' => 'Cable Plan Updated Successfully']);
        session()->flash('success', 'Cable Plan Updated Successfully');
        return redirect()->to(route('admin.utility.cable.plan', [$this->vendor->id, $this->cable->id]));
    }

    public function render()
    {
        return view('livewire.admin.utility.cable.plan.edit');
    }
}
