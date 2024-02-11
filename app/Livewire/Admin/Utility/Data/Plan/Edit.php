<?php

namespace App\Livewire\Admin\Utility\Data\Plan;

use Livewire\Component;
use App\Models\Data\DataPlan;
use App\Models\Data\DataType;
use Livewire\Attributes\Rule;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;
class Edit extends Component
{
    public $vendor;
    public $network;
    public $type;
    public $plan;

    #[Rule('required|integer')]
    public $api_id;
    #[Rule('required|string')]
    public $plan_size;
    #[Rule('required|numeric')]
    public $amount;
    #[Rule('required|string')]
    public $validity;
    #[Rule('boolean')]
    public $status;

    public function mount(DataPlan $plan, DataType $type, DataVendor $vendor, DataNetwork $network)
    {
        $this->vendor = $vendor;
        $this->network = $network;
        $this->type = $type;
        $this->plan = $plan;
        $this->api_id = $this->plan->data_id;
        $this->plan_size = $this->plan->size;
        $this->amount = $this->plan->amount;
        $this->validity = $this->plan->validity;
        $this->status = $this->plan->status ? true : false;
        $this->authorize('edit data utility');
    }

    public function update()
    {
        $this->validate();

        if ($this->api_id !== $this->plan->data_id) {
            
            $checkIfApiIdExists = DataPlan::whereVendorId($this->vendor->id)->whereNetworkId($this->network->network_id)->whereTypeId($this->type->id)->whereDataId($this->api_id)->where('id', '!=', $this->plan->id)->count();
            
            if ($checkIfApiIdExists > 0) return $this->dispatch('error-toastr', ['message' => "API ID already exists on vendor({$this->vendor->name}). Please verify the API ID"]);
        }

        $this->plan->update([
            'data_id'   =>  $this->api_id,
            'size'      =>  $this->plan_size,
            'amount'    =>  $this->amount,
            'validity'  =>  $this->validity,
            'status'    =>  $this->status
        ]);

        $this->dispatch('success-toastr', ['message' => 'Data Plan Updated Successfully']);
        session()->flash('success', 'Data Plan Updated Successfully');
        return redirect()->to(route('admin.utility.data.plan', [$this->vendor->id, $this->network->id, $this->type->id]));
    }

    public function render()
    {
        return view('livewire.admin.utility.data.plan.edit');
    }
}
