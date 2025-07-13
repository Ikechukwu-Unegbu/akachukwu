<?php

namespace App\Livewire\Admin\Utility\Data;

use App\Models\Vendor;
use Livewire\Component;
use App\Services\Admin\Activity\ActivityLogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\ActivityConstants;
use App\Helpers\GeneralHelpers;

class Discount extends Component
{
    public $vendor;
    public $discounts = [];

    public function mount(Vendor $vendor)
    {
        $this->vendor = $vendor;
        $this->discounts = $this->vendor->networks->pluck('data_discount', 'id')->toArray();
        $this->authorize('view data utility');
    }

    public function update()
    {
        $this->validate([
            'discounts' => ['array'],
            'discounts.*' => ['required', 'numeric', 'min:0']
        ], [
            'discounts.*.required' => 'The discount field is required.',
            'discounts.*.numeric' => 'The discount must be a number.',
            'discounts.*.min' => 'The discount must be at least :min.',
        ]);

        $batchId = GeneralHelpers::generateUniqueNumericRef('activity_logs');
        foreach ($this->discounts as $key => $value) {
            $this->vendor->networks->find($key)?->update(['data_discount' => $value]);
            ActivityLogService::log([
                'activity'=>"Update",
                'description'=>"Mass percentage update - vendor: ".$this->vendor->name." and network: ".$this->vendor->networks->find($key)?->name,
                'ref_id'=>$batchId,
                'type'=>ActivityConstants::DATANETWORK,
            ]);
        }

        $this->dispatch('success-toastr', ['message' => 'Data Network Discount Updated Successfully']);
        session()->flash('success', 'Data Network Discount Updated Successfully');
        return redirect()->to(route('admin.utility.data') . "?vendor={$this->vendor->id}");
    }

    public function render()
    {
        return view('livewire.admin.utility.data.discount', [
            'networks'  => $this->vendor->networks
        ]);
    }
}
