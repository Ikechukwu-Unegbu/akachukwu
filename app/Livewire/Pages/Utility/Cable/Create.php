<?php

namespace App\Livewire\Pages\Utility\Cable;

use Livewire\Component;
use App\Models\Beneficiary;
use App\Models\Utility\Cable;
use App\Models\Data\DataVendor;
use App\Models\Utility\CablePlan;
use App\Services\Cable\CableService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Utility\CableTransaction;
use App\Services\Account\AccountBalanceService;
use App\Services\Beneficiary\BeneficiaryService;

class Create extends Component
{
    public $vendor;
    public $cable_name;
    public $iuc_number;
    public $cable_plan;

    public $customer;
    public $validate_action = false;
    public $beneficiary_modal = false;

    public function mount()
    {
        $this->vendor = DataVendor::whereStatus(true)->first();
    }

    public function updatedCableName()
    {
        $this->cable_plan = null;
    }

    public function updatedIucNumber()
    {
        $this->validate_action = false;
        $this->customer = null;
    }

    public function submit()
    {
        $this->validate([
            'cable_name'    =>  'required|integer',
            'iuc_number'    =>  'required|numeric',
            'cable_plan'    =>  'required|integer'
        ]);

        if (!$this->validate_action) {
            $cableValidate = CableService::validateIUCNumber($this->vendor->id, $this->iuc_number, $this->cable_name); 

            if (!$cableValidate->status) {
                return $this->dispatch('error-toastr', ['message' => $cableValidate->message]);
            }
    
            if ($cableValidate->status) {
                $this->customer = $cableValidate->data->name;
                $this->validate_action = true;
                return $this->dispatch('success-toastr', ['message' => $cableValidate->message]);
            }

        }

        if ($this->validate_action) {
            $cableTransaction = CableService::create($this->vendor->id, $this->cable_name, $this->cable_plan, $this->iuc_number, $this->customer);

            if (!$cableTransaction->status) {
                return $this->dispatch('error-toastr', ['message' => $cableTransaction->message]);
            }
    
            if ($cableTransaction->status) {
                $this->dispatch('success-toastr', ['message' => $cableTransaction->message]);
                session()->flash('success',  $cableTransaction->message);
                return redirect()->route('dashboard');
            }
        }
    }

    public function validateIUCNumber()
    {
        $this->validate([
            'cable_name'    =>  'required|integer',
            'iuc_number'    =>  'required',
            'cable_plan'    =>  'required|integer'
        ]);


        /*
        try {

            $cable = Cable::whereVendorId($this->vendor?->id)->whereCableId($this->cable_name)->first();

            $response = Http::withHeaders([
                'Authorization' => "Token " . $this->vendor->token,
                'Content-Type' => 'application/json',
            ])->get(str_replace("/api", "", $this->vendor->api). "/ajax/validate_iuc/?smart_card_number={$this->iuc_number}&cablename={$cable->cable_name}");
    
            $response = $response->object();
            
            if (!$response->invalid) {
                $this->customer = $response->name;
                $this->validate_action = true;
                $this->dispatch('success-toastr', ['message' => "IUC validated. Click Continue to proceed payment."]);;
                return true;
            }

            return $this->dispatch('error-toastr', ['message' => 'Invalid IUC/SMARTCARD. Please provide a valid IUC/SMARTCARD']);
        
        } catch (\Exception $e) {
            
            return $this->dispatch('error-toastr', ['message' => 'Unable to Perform Cable transaction. Please check your network connection.']);

        }
        */
    }

    public function beneficiary_action()
    {
        $this->beneficiary_modal = true;
    }

    public function beneficiary($id)
    {
        $beneficiary = Beneficiary::find($id);
        $this->iuc_number = $beneficiary->beneficiary;
        $meta = json_decode($beneficiary->meta_data);
        $this->cable_name = $meta->cable_id;
        $this->cable_plan = $meta->cable_plan_id;
        $this->beneficiary_modal = false;
        return;
    }

    public function render()
    {
        return view('livewire.pages.utility.cable.create', [
            'cables'        =>  $this->vendor ? Cable::whereVendorId($this->vendor?->id)->whereStatus(true)->get() : [],
            'cable_plans'   =>  $this->vendor && $this->cable_name ? CablePlan::whereVendorId($this->vendor?->id)->whereCableId($this->cable_name)->whereStatus(true)->get() : [],
            'beneficiaries' =>  BeneficiaryService::get('cable')
        ]);
    }
}
