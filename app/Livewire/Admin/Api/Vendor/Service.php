<?php

namespace App\Livewire\Admin\Api\Vendor;

use App\Models\Vendor;
use App\Models\VendorServiceMapping;
use Livewire\Component;

class Service extends Component
{
    
    public $vendor;
    public $service;

    public function mount()
    {
        $this->authorize('view vendor api');   
    }

    public function updateModal(VendorServiceMapping $service)
    {
        $this->service = $service;
        $this->vendor = $this->service->vendor->id;
        return true;
    }

    public function updateService()
    {
        $this->service->update(['vendor_id' => $this->vendor]);
        $this->dispatch('success-toastr', ['message' => 'Service Updated Successfully']);
        session()->flash('success', 'Service Updated Successfully');
        $this->redirect(url()->previous());
    }

    public function render()
    {
        return view('livewire.admin.api.vendor.service', [
            'vendor_services'  => VendorServiceMapping::with('vendor')->orderBy('service_type')->get(),
            'vendors'          => Vendor::get()
        ]);
    }
}
