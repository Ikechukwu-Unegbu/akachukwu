<?php

namespace App\Livewire\Admin\Utility\Data;

use App\Models\Data\DataNetwork;
use App\Models\Data\DataVendor;
use Livewire\Component;

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
    }

    public function update()
    {
        $this->validate([
            'api_id'        =>  'required|integer',
            'network_title' =>  'required|string',
            'status'        =>  'boolean'
        ]);

        if ($this->api_id !== $this->network->network_id) {
            
            $checkIfApiIdExists = DataNetwork::whereVendorId($this->vendor->id)->whereNetworkId($this->api_id)->where('id', '!=', $this->network->id)->count();

            if ($checkIfApiIdExists > 0) return $this->dispatch('error-toastr', ['message' => "API ID already exists on vendor({$this->vendor->name}). Please verify the API ID"]);
        }

        $this->network->update([
            'network_id'    =>  $this->api_id,    
            'name'          =>  trim($this->network_title),
            'status'        =>  $this->status
        ]);

        $this->dispatch('success-toastr', ['message' => 'Data Network Updated Successfully']);
        session()->flash('success', 'Data Network Updated Successfully');
        return redirect()->to(route('admin.utility.data') . "?vendor={$this->vendor->id}");
    }

    public function render()
    {
        return view('livewire.admin.utility.data.edit');
    }
}
