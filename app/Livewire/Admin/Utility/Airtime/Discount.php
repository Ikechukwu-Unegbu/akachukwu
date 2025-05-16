<?php

namespace App\Livewire\Admin\Utility\Airtime;

use App\Models\Vendor;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Data\DataNetwork;

use App\Services\Admin\Activity\ActivityLogService;


class Discount extends Component
{
    public $vendor;
    public $discounts = [];

    public function mount(Vendor $vendor)
    {
        $this->vendor = $vendor;
        $this->discounts = $this->vendor->networks->pluck('airtime_discount', 'id')->toArray();
        $this->authorize('view airtime utility');
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

        DB::transaction(function(){
            foreach ($this->discounts as $key => $value) {
                ActivityLogService::log([
                    'activity'=>"Update",
                    'description'=>'Updating '.DataNetwork::find($key)->name.' Airtime Discount - '.DataNetwork::find($key)->vendor->name,
                    'type'=>'DataNetwork',
                    'resource'=>serialize(DataNetwork::find($key))
                ]);
                $this->vendor->networks->find($key)?->update(['airtime_discount' => $value]);
            }

        });

    

        $this->dispatch('success-toastr', ['message' => 'Airtime Network Discount Updated Successfully']);
        session()->flash('success', 'Airtime Network Discount Updated Successfully');
        return redirect()->to(route('admin.utility.airtime') . "?vendor={$this->vendor->id}");
    }
    
    public function render()
    {
        return view('livewire.admin.utility.airtime.discount', [
            'networks'  => $this->vendor->networks
        ]);
    }
}
