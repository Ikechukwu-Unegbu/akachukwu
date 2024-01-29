<?php

namespace App\Livewire\Admin\Utility\Cable;

use Livewire\Component;
use App\Models\Utility\Cable;
use Livewire\Attributes\Rule;
use App\Models\Data\DataVendor;

class Edit extends Component
{
    public $cable;
    public $vendor;
    #[Rule('required|integer')]
    public $api_id;
    #[Rule('required|string')]
    public $cable_name;
    #[Rule('required|boolean')]
    public $status;
    
    public function mount(Cable $cable, DataVendor $vendor)
    {
        $this->cable = $cable;
        $this->vendor = $vendor;
        $this->api_id = $cable->cable_id;
        $this->cable_name = $this->cable->cable_name;
        $this->status = $this->cable->status ? true : false;
    }

    public function update()
    {
        $this->validate();
        
        if ($this->api_id !== $this->cable->cable_id) {
            
            $checkApiIdExists = Cable::whereVendorId($this->vendor->id)->whereCableId($this->api_id)->where('id', '!=', $this->cable->id)->count();
            
            if ($checkApiIdExists > 0) 
                return $this->dispatch('error-toastr', [
                    'message' => "API ID already exists on vendor({$this->vendor->name}). Please verify the API ID"
                ]);
        }

        $this->cable->update([
            'cable_id'      =>  $this->api_id,
            'cable_name'    =>  $this->cable_name,
            'status'        =>  $this->status
        ]);

        $this->dispatch('success-toastr', ['message' => 'Cable TV Updated Successfully']);
        session()->flash('success', 'Cable TV Updated Successfully');
        return redirect()->to(route('admin.utility.cable') . "?vendor={$this->vendor->id}");

        
    }

    public function render()
    {
        return view('livewire.admin.utility.cable.edit');
    }
}
