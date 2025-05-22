<?php

namespace App\Livewire\Admin\ModuleControl;

use App\Models\Vendor;
use Livewire\Component;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
use App\Models\Data\DataType;
use App\Models\Data\DataNetwork;

use Illuminate\Support\Facades\Auth;
use App\Services\Admin\Activity\ActivityLogService;
use Illuminate\Support\Facades\DB;



class Index extends Component
{
    public function handleNetwork($id)
    {
        DB::transaction(function()use($id){
            $network = DataNetwork::find($id);
            $network->update(['status' => !$network->status]);
    
            ActivityLogService::log([
                'activity'=>"Update",
                'description'=>"Changing Network Status for ".$network->name.' of '.$network->vendor->name,
                'type'=>'DataNetwork',
                // 'resource_owner_id'=>$user->id, 
                'resource'=>serialize($network), 
                'new_resource'=>serialize(DataNetwork::find($id)),
            ]);
        });
        $this->dispatch('success-toastr', ['message' => "Network Updated Successfully."]);
    }

    public function handleDataType($id)
    {
        $dataType = DataType::find($id);
        $dataType->update(['status' => !$dataType->status]);
        ActivityLogService::log([
            'activity'=>"Update",
            'description'=>"Changing Plan Status for ".$dataType->name.' of '.$dataType->dataNetwork->name,
            'type'=>'DataType',
            // 'resource_owner_id'=>$user->id, 
            'resource'=>serialize($dataType), 
            'new_resource'=>serialize(DataType::find($id)),
        ]);
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
            ActivityLogService::log([
                'activity'=>"Update",
                'description'=>"Updating ".$column." to ". $newValue,
                'type'=>'SiteSetting',
            ]);
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
