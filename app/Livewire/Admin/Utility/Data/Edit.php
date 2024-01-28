<?php

namespace App\Livewire\Admin\Utility\Data;

use App\Models\Data\DataNetwork;
use App\Models\Data\DataVendor;
use Livewire\Component;

class Edit extends Component
{
    public $vendor;
    public $network;
    public $network_title;
    public $status;

    public function mount(DataVendor $vendor, DataNetwork $network)
    {
        $this->vendor = $vendor;
        $this->network = $network;
        $this->network_title = $this->network->name;
        $this->status = $this->network->status ? true : false;
    }

    public function update()
    {
        $this->validate([
            'network_title' =>  'required|string',
            'status'        =>  'boolean'
        ]);

        $this->network->update([
            'name'      =>  trim($this->network_title),
            'status'    =>  $this->status
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
