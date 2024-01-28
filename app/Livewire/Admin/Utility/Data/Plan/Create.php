<?php

namespace App\Livewire\Admin\Utility\Data\Plan;

use Livewire\Component;
use App\Models\Data\DataPlan;
use App\Models\Data\DataType;
use Livewire\Attributes\Rule;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;

class Create extends Component
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
    public $status = true;

    public function mount(DataType $type, DataVendor $vendor, DataNetwork $network)
    {
        $this->vendor = $vendor;
        $this->network = $network;
        $this->type = $type;
    }

    public function store()
    {
        $this->validate();

        $checkIfApiIdExists = DataPlan::whereVendorId($this->vendor->id)->whereNetworkId($this->network->network_id)->whereTypeId($this->type->id)->whereDataId($this->api_id)->count();
            
        if ($checkIfApiIdExists > 0) return $this->dispatch('error-toastr', ['message' => "API ID already exists on vendor({$this->vendor->name}). Please verify the API ID"]);

        DataPlan::create([
            'vendor_id'     =>  $this->vendor->id,
            'network_id'    =>  $this->network->network_id,
            'type_id'       =>  $this->type->id,
            'data_id'       =>  $this->api_id,
            'size'          =>  $this->plan_size,
            'amount'        =>  $this->amount,
            'validity'      =>  $this->validity,
            'status'        =>  $this->status
        ]);

        $this->dispatch('success-toastr', ['message' => 'Data Plan Added Successfully']);
        session()->flash('success', 'Data Plan Added Successfully');
        return redirect()->to(route('admin.utility.data.plan', [$this->vendor->id, $this->network->id, $this->type->id]));
    }

    public function render()
    {
        return view('livewire.admin.utility.data.plan.create');
    }
}
