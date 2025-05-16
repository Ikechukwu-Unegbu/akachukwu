<?php

namespace App\Livewire\Admin\Utility\Data\Type;

use Livewire\Component;
use App\Models\Data\DataType;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;
use App\Services\Admin\Activity\ActivityLogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Edit extends Component
{
    public $vendor;
    public $network;
    public $type;
    public $data_type_title;
    public $data_type_percentage_referall_pay;
    public $status;

    public function mount(DataType $type, DataVendor $vendor, DataNetwork $network)
    {
        $this->vendor = $vendor;
        $this->network = $network;
        $this->type = $type;
        $this->data_type_title = $this->type->name;
        $this->data_type_percentage_referall_pay = $this->type->referral_pay;
        $this->status = $this->type->status ? true : false;
        $this->authorize('edit data utility');
    }

    public function update()
    {
        $this->validate([
            'data_type_title' =>  'required|string',
            'status'          =>  'boolean',
            'data_type_percentage_referall_pay'=>'required'
        ]);

        DB::transaction(function(){
         
            ActivityLogService::log([
                'activity'=>"Update",
                'description'=>"Updating ".$this->type->name ." of ".$this->network->name." of ". $this->network->vendor->name,
                'type'=>'DataType',
                'resource'=>serialize($this->type)
            ]);
            $this->type->update([
                'name'      =>  trim($this->data_type_title),
                'status'    =>  $this->status,
                'referral_pay' => $this->data_type_percentage_referall_pay
            ]);
        });

  

        $this->dispatch('success-toastr', ['message' => 'Data Type Updated Successfully']);
        session()->flash('success', 'Data Type Updated Successfully');
        return redirect()->to(route('admin.utility.data.type', [$this->vendor->id, $this->network->id]));
    }
    
    public function render()
    {
        return view('livewire.admin.utility.data.type.edit');
    }
}
