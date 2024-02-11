<?php

namespace App\Livewire\Admin\Utility\Data\Type;

use Livewire\Component;
use App\Models\Data\DataType;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;

class Edit extends Component
{
    public $vendor;
    public $network;
    public $type;
    public $data_type_title;
    public $status;

    public function mount(DataType $type, DataVendor $vendor, DataNetwork $network)
    {
        $this->vendor = $vendor;
        $this->network = $network;
        $this->type = $type;
        $this->data_type_title = $this->type->name;
        $this->status = $this->type->status ? true : false;
        $this->authorize('edit data utility');
    }

    public function update()
    {
        $this->validate([
            'data_type_title' =>  'required|string',
            'status'          =>  'boolean'
        ]);

        $this->type->update([
            'name'      =>  trim($this->data_type_title),
            'status'    =>  $this->status
        ]);

        $this->dispatch('success-toastr', ['message' => 'Data Type Updated Successfully']);
        session()->flash('success', 'Data Type Updated Successfully');
        return redirect()->to(route('admin.utility.data.type', [$this->vendor->id, $this->network->id]));
    }
    
    public function render()
    {
        return view('livewire.admin.utility.data.type.edit');
    }
}
