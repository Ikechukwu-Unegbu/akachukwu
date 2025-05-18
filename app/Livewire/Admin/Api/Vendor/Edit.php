<?php

namespace App\Livewire\Admin\Api\Vendor;

use App\Models\Vendor;
use Livewire\Component;
use Livewire\Attributes\Rule;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\Admin\Activity\ActivityLogService;


class Edit extends Component
{
    public $vendor;
    #[Rule('required|string')]
    public $name;
    #[Rule('required|url')]
    public $api;
    #[Rule('required|string')]
    public $token;
    #[Rule('nullable|string')]
    public $public_key;
    #[Rule('nullable|string')]
    public $secret_key;
    #[Rule('required|boolean')]
    public $status;


    public function mount(Vendor $vendor)
    {
        $this->vendor = $vendor;
        $this->name = $this->vendor->name;
        $this->api = $this->vendor->api;
        $this->token = $this->vendor->token;
        $this->public_key = $this->vendor->public_key;
        $this->secret_key = $this->vendor->secret_key;
        $this->status = $this->vendor->status ? true : false;
        $this->authorize('edit vendor api');
    }

    public function update()
    {
        $validated = $this->validate();
        
        DB::transaction(function(){
            
            if ($this->status) $this->vendor->setAllStatusToFalse();

            $this->vendor->update($validated);

            ActivityLogService::log([
                'activity'=>"Update",
                'description'=>'Editing Single Vendor: '.$this->vendor->name,
                'type'=>'Vendors',
                'tags'=>['Edit','Update', 'Vendors']
            ]);

        });
        $this->dispatch('success-toastr', ['message' => 'Vendor Updated Successfully']);
        session()->flash('success', 'Vendor Updated Successfully');
        return redirect()->to(route('admin.api.vendor'));
    }

    public function render()
    {
        return view('livewire.admin.api.vendor.edit');
    }
}
