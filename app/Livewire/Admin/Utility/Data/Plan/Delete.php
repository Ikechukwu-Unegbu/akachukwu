<?php

namespace App\Livewire\Admin\Utility\Data\Plan;

use Livewire\Component;
use App\Models\Data\DataPlan;
use App\Models\Data\DataType;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;

class Delete extends Component
{
    public $vendor;
    public $network;
    public $type;
    public $plan;
    
    public function mount(DataPlan $plan, DataType $type, DataVendor $vendor, DataNetwork $network)
    {
        $this->vendor = $vendor;
        $this->network = $network;
        $this->type = $type;
        $this->plan = $plan;
        $this->authorize('delete data utility');
    }

    public function destroy()
    {
        $this->plan->delete();
        $this->dispatch('success-toastr', ['message' => 'Data Plan Deleted Successfully']);
        session()->flash('success', 'Data Plan Deleted Successfully');
        return redirect()->to(route('admin.utility.data.plan', [$this->vendor->id, $this->network->id, $this->type->id]));
    }

    public function render()
    {
        return view('livewire.admin.utility.data.plan.delete');
    }
}
