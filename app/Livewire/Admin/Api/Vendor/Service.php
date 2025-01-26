<?php

namespace App\Livewire\Admin\Api\Vendor;

use App\Models\AirtimeVendorMapping;
use App\Models\Data\DataNetwork;
use App\Models\Vendor;
use App\Models\VendorServiceMapping;
use Livewire\Component;

class Service extends Component
{
    
    public $vendor;
    public $service;
    public $airtimeService;
    public $network_name;

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

    public function updateAirtimeNetworkModal($network)
    {
        $airtimeVendorMapping = AirtimeVendorMapping::where('network', $network)->first();
        $this->network_name = $network;
        if ($airtimeVendorMapping) {
            $this->airtimeService = $airtimeVendorMapping;
            $this->vendor = $this->airtimeService?->vendor?->id;
            return true;
        }
        return false;
    }

    public function updateService()
    {
        $this->service->update(['vendor_id' => $this->vendor]);
        $this->dispatch('success-toastr', ['message' => 'Service Updated Successfully']);
        session()->flash('success', 'Service Updated Successfully');
        $this->redirect(url()->previous());
    }

    public function updateAirtimeService()
    {
        if (!DataNetwork::where('name', $this->network_name)->where('vendor_id', $this->vendor)->exists()) {
            $this->dispatch('error-toastr', [
                'message' => "Vendor could not be assigned to '{$this->network_name}' because the vendor network ID was not found."
            ]);
            return;
        }       

        AirtimeVendorMapping::updateOrCreate(['network' => $this->network_name], ['vendor_id' => $this->vendor]);
        $this->dispatch('success-toastr', ['message' => 'Airtime Service Updated Successfully']);
        session()->flash('success', 'Airtime Service Updated Successfully');
        $this->redirect(url()->previous());
    }

    public function render()
    {
        return view('livewire.admin.api.vendor.service', [
            'vendor_services'  => VendorServiceMapping::with('vendor')->orderBy('service_type')->get(),
            'vendors'          => Vendor::get(),
            'networks'         => DataNetwork::with('airtimeMapping')->get()->unique('name')
        ]);
    }
}
