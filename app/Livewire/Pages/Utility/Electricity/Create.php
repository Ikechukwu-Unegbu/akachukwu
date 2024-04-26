<?php

namespace App\Livewire\Pages\Utility\Electricity;

use Livewire\Component;
use Livewire\Attributes\Rule;
use App\Models\Data\DataVendor;
use App\Models\Utility\Electricity;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Utility\ElectricityTransaction;
use App\Services\Account\AccountBalanceService;
use App\Services\Electricity\ElectricityService;

class Create extends Component
{
    public $vendor;
    public $meter_types = [1 => "PREPAID", 2 => "POSTPAID"];

    #[Rule('required|integer')]
    public $disco_name;
    #[Rule('required|integer|in:1,2')]
    public $meter_type;
    #[Rule('required|numeric')]
    public $amount;
    #[Rule('required|numeric')]
    public $meter_number;
    #[Rule(['required', 'regex:/^0(70|80|81|90|91|80|81|70)\d{8}$/'])]
    public $customer_phone_number;

    public $customer_name;
    public $customer_address;
    public $validate_action = false;


    public function mount()
    {
        $this->vendor = DataVendor::whereStatus(true)->first();
    }

    public function updatedMeterNumber()
    {
        $this->validate_action = false;
        $this->customer_name = null;
        $this->customer_address = null;
    }

    public function updatedDiscoName()
    {
        $this->validate_action = false;
        $this->customer_name = null;
        $this->customer_address = null;
    }

    public function updatedMeterType()
    {
        $this->validate_action = false;
        $this->customer_name = null;
        $this->customer_address = null;
    }

    public function submit()
    {
        $this->validate();

        if (!$this->validate_action) {
            $electricityValidate = ElectricityService::validateMeterNumber($this->vendor->id, $this->meter_number, $this->disco_name, $this->meter_type); 
    
            if (!$electricityValidate->status) {
                return $this->dispatch('error-toastr', ['message' => $electricityValidate->message]);
            }
    
            if ($electricityValidate->status) {
                $this->customer_name = $electricityValidate->data->name;
                $this->customer_address = $electricityValidate->data->address;
                $this->validate_action = true;
                return $this->dispatch('success-toastr', ['message' => $electricityValidate->message]);
            }
        }

        if ($this->validate_action) {
            $electricityTransaction = ElectricityService::create($this->vendor->id, $this->disco_name, $this->meter_number, $this->meter_type, $this->amount, $this->customer_name, $this->customer_phone_number, $this->customer_address); 
            
            if (!$electricityTransaction->status) {
                return $this->dispatch('error-toastr', ['message' => $electricityTransaction->message]);
            }
    
            if ($electricityTransaction->status) {
                $this->dispatch('success-toastr', ['message' => $electricityTransaction->message]);
                session()->flash('success',  $electricityTransaction->message);
                return redirect()->route('dashboard');
            }
        
        }
    }


    public function validateMeterNumber() 
    {

        $this->validate();

        try {

            $meterType =  $this->meter_type == 1 ? 'Prepaid' : 'Postpaid';
            $disco = Electricity::whereVendorId($this->vendor->id)->whereDiscoId($this->disco_name)->first()->disco_name;

            $response = Http::withHeaders([
                'Authorization' => "Token " . $this->vendor->token,
                'Content-Type' => 'application/json',
            ])->get("{$this->vendor->api}/validatemeter?meternumber={$this->meter_number}&disconame={$disco}&mtype={$meterType}");
            
            $response = $response->object();

            if (!$response->invalid) {
                $this->customer_name = $response->name;
                $this->customer_address = $response->address;
                $this->validate_action = true;

                return $this->dispatch('success-toastr', ['message' => "Meter Number validated. Click Continue to proceed payment."]);;
            }

            return $this->dispatch('error-toastr', ['message' => "Oops! The Meter Number provided is Invalid ({$this->meter_number})."]);

        } catch (\Exception $e) {

            return $this->dispatch('error-toastr', ['message' => 'Unable to Perform Bill transaction. Please check your network connection.']);
        
        }
    }

    public function render()
    {
        return view('livewire.pages.utility.electricity.create', [
            'electricity' => $this->vendor ? Electricity::whereVendorId($this->vendor?->id)->whereStatus(true)->get() : []
        ]);
    }
}
