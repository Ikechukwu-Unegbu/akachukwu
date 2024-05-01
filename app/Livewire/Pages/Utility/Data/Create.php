<?php

namespace App\Livewire\Pages\Utility\Data;

use Livewire\Component;
use App\Models\Data\DataPlan;
use App\Models\Data\DataType;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;
use App\Services\Data\DataService;

class Create extends Component
{
    public $network;
    public $vendor;
    public $dataType;
    public $phone_number;
    public $amount;
    public $plan;


    public function mount()
    {
        $this->vendor = DataVendor::whereStatus(true)->first();
        $this->network = DataNetwork::whereVendorId($this->vendor?->id)->whereStatus(true)->first()?->network_id;
    }

    public function updatedNetwork()
    {
        $this->plan = null;
        $this->amount = null;
        $this->dataType = null;
    }

    public function updatedPlan()
    {
        $this->amount = DataPlan::whereVendorId($this->vendor?->id)->whereNetworkId($this->network)->whereDataId($this->plan)->first()?->amount;
        $this->amount = number_format($this->amount, 1);
    }

    public function updatedDataType()
    {
        $this->plan = null;
        $this->amount = null;
    }

    public function submit()
    {
        $this->validate([
            'network'       =>  'required|integer',
            'dataType'      =>  'required|integer',
            'plan'          =>  'required|integer',
            'phone_number'  =>  ['required', 'regex:/^0(70|80|81|90|91|80|81|70)\d{8}$/'],
        ]);

        $dataTransaction = DataService::create(
            $this->vendor->id, 
            $this->network, 
            $this->dataType, 
            $this->plan, 
            $this->phone_number
        );

        if (!$dataTransaction->status) {
            return $this->dispatch('error-toastr', ['message' => $dataTransaction->message]);
        }

        if ($dataTransaction->status) {
            $this->dispatch('success-toastr', ['message' => $dataTransaction->message]);
            session()->flash('success',  $dataTransaction->message);
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        return view('livewire.pages.utility.data.create', [

            'networks'      =>  $this->vendor ? DataNetwork::whereVendorId($this->vendor->id)->whereStatus(true)->get() : [],
            'dataTypes'     =>  $this->vendor && $this->network ? DataType::whereVendorId($this->vendor->id)->whereNetworkId($this->network)->whereStatus(true)->get() : [],
            'plans'         =>  $this->vendor && $this->network && $this->dataType ? DataPlan::with('type')->whereVendorId($this->vendor->id)->whereNetworkId($this->network)->whereTypeId($this->dataType)->whereStatus(true)->get() : []
        ]);
    }
}
