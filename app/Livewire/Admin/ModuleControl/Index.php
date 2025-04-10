<?php

namespace App\Livewire\Admin\ModuleControl;

use App\Models\Vendor;
use Livewire\Component;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
use App\Models\Data\DataType;
use App\Models\Data\DataNetwork;

class Index extends Component
{
    public function handleNetwork($id)
    {
        $network = DataNetwork::find($id);
        $network->update(['status' => !$network->status]);
        $this->dispatch('success-toastr', ['message' => "Network Updated Successfully."]);
    }
    public function handleDataType($id)
    {
        $dataType = DataType::find($id);
        $dataType->update(['status' => !$dataType->status]);
        $this->dispatch('success-toastr', ['message' => "Data Type Updated Successfully."]);
    }

    public function handleSiteSettings($column)
    {
        try {
            $settings = SiteSetting::first();
            
            if (!$settings) {
                return $this->dispatch('error-toastr', [
                    'message' => "Site settings not found!"
                ]);
            }
    
            if (!array_key_exists($column, $settings->getAttributes())) {
                return $this->dispatch('error-toastr', [
                    'message' => "Invalid setting field!"
                ]);
            }
    
            $newValue = !$settings->$column;
            $settings->update([$column => $newValue]);
    
            return $this->dispatch('success-toastr', [
                'message' => "Setting updated Successfully."
            ]);
    
        } catch (\Exception $e) {
            return $this->dispatch('error-toastr', [
                'message' => "Error updating settings: ".$e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.module-control.index', [
            'vendors'   =>  Vendor::withCount(['networks'])->with(['networks', 'networks.dataTypes'])->get(),
        ]);
    }
}
