<?php

namespace App\Livewire\Admin\Utility\Data;

use App\Models\Data\DataNetwork;
use App\Models\Data\DataVendor;
use Livewire\Component;


use App\Services\Admin\Activity\ActivityLogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\ActivityConstants;

class Edit extends Component
{
    public $vendor;
    public $network;
    public $api_id;
    public $network_title;
    public $status;

    public function mount(DataVendor $vendor, DataNetwork $network)
    {
        $this->vendor = $vendor;
        $this->network = $network;
        $this->api_id = $this->network->network_id;
        $this->network_title = $this->network->name;
        $this->status = $this->network->status ? true : false;
        $this->authorize('edit data utility');
    }

    public function update()
    {
        // dd("we are here.");
        $this->validate([
            'api_id'        =>  'required|integer',
            'network_title' =>  'required|string',
            'status'        =>  'boolean'
        ]);

        DB::transaction(function(){

            if ($this->api_id !== $this->network->network_id) {
            
                $checkIfApiIdExists = DataNetwork::whereVendorId($this->vendor->id)->whereNetworkId($this->api_id)->where('id', '!=', $this->network->id)->count();
    
                if ($checkIfApiIdExists > 0) return $this->dispatch('error-toastr', ['message' => "API ID already exists on vendor({$this->vendor->name}). Please verify the API ID"]);
            }
    
            $this->network->update([
                'network_id'    =>  $this->api_id,    
                'name'          =>  trim($this->network_title),
                'status'        =>  $this->status
            ]);

            
            ActivityLogService::log([
                'activity'=>"Update",
                'description'=>"Updating (for data) ".$this->network->name ." of ".$this->vendor->name.'.',
                'type'=>ActivityConstants::DATANETWORK,
                'resource'=>serialize($this->network)
            ]);
        });

     
        $this->dispatch('success-toastr', ['message' => 'Data Network Updated Successfully']);
        session()->flash('success', 'Data Network Updated Successfully');
        return redirect()->to(route('admin.utility.data') . "?vendor={$this->vendor->id}");
    }

    public function render()
    {
        return view('livewire.admin.utility.data.edit');
    }
}
