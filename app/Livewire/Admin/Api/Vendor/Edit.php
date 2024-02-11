<?php

namespace App\Livewire\Admin\Api\Vendor;

use App\Models\Vendor;
use Livewire\Component;
use Livewire\Attributes\Rule;

class Edit extends Component
{
    public $vendor;
    #[Rule('required|string')]
    public $name;
    #[Rule('required|url')]
    public $api;
    #[Rule('required|string')]
    public $token;
    #[Rule('required|boolean')]
    public $status;


    public function mount(Vendor $vendor)
    {
        $this->vendor = $vendor;
        $this->name = $this->vendor->name;
        $this->api = $this->vendor->api;
        $this->token = $this->vendor->token;
        $this->status = $this->vendor->status ? true : false;
        $this->authorize('edit vendor api');
    }

    public function update()
    {
        $validated = $this->validate();
        
        if ($this->status) $this->vendor->setAllStatusToFalse();

        $this->vendor->update($validated);

        $this->dispatch('success-toastr', ['message' => 'Vendor Updated Successfully']);
        session()->flash('success', 'Vendor Updated Successfully');
        return redirect()->to(route('admin.api.vendor'));
    }

    public function render()
    {
        return view('livewire.admin.api.vendor.edit');
    }
}
