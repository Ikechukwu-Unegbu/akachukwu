<?php

namespace App\Livewire\Admin\Utility\Cable\Plan;

use Livewire\Component;
use App\Models\Utility\Cable;
use App\Models\Data\DataVendor;
use App\Models\Utility\CablePlan;

class Delete extends Component
{
    public $vendor;
    public $cable;
    public $plan;

    public function mount(Cable $cable, CablePlan $plan, DataVendor $vendor)
    {
        $this->vendor = $vendor;
        $this->cable = $cable;
        $this->plan = $plan;
    }


    public function destroy()
    {
        $this->plan->delete();
        $this->dispatch('success-toastr', ['message' => 'Cable Plan Deleted Successfully']);
        session()->flash('success', 'Cable Plan Deleted Successfully');
        return redirect()->to(route('admin.utility.cable.plan', [$this->vendor->id, $this->cable->id]));
    }

    public function render()
    {
        return view('livewire.admin.utility.cable.plan.delete');
    }
}
