<?php

namespace App\Livewire\Pages\Utility\Electricity;

use Livewire\Component;
use App\Models\Beneficiary;
use Livewire\Attributes\Rule;
use App\Models\Data\DataVendor;
use App\Models\Utility\Electricity;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Traits\ResolvesVendorService;
use App\Services\Account\UserPinService;
use App\Models\Utility\ElectricityTransaction;
use Illuminate\Validation\ValidationException;
use App\Services\Account\AccountBalanceService;
use App\Services\Beneficiary\BeneficiaryService;
use App\Services\Electricity\ElectricityService;

class Create extends Component
{
    use ResolvesVendorService;
    public $vendor;
    public $meter_types = [1 => "PREPAID", 2 => "POSTPAID"];

    #[Rule('required')]
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
    public $beneficiary_modal = false;

    public $pin;
    public $form_action = false;
    public $validate_pin_action = false;

    public function mount()
    {
        $this->vendor = $this->getVendorService('electricity');
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

    public function closeModal()
    {
        $this->validate_pin_action = false;
        $this->form_action = false;
        $this->pin = "";
        return;
    }

    public function addDigit($digit)
    {
        if (strlen($this->pin) < 4) {
            $this->pin .= $digit;
        }
    }

    public function clearPin()
    {
        $this->pin = '';
    }

    public function deletePin()
    {
        $this->pin = substr($this->pin, 0, -1);
    }

    public function validatePin()
    {
        $this->validate([
            'pin' => 'required|numeric|digits:4'
        ]);

        $userPinService = UserPinService::validatePin(Auth::user(), $this->pin);

        if (!$userPinService) {
            throw ValidationException::withMessages([
                'pin' => __('The PIN provided is incorrect. Provide a valid PIN.'),
            ]);
        }

        return $this->validate_pin_action = true;
    }

    public function validateIUC()
    {
        $this->validate();

        if (!$this->validate_action) {
            $electricityValidate = ElectricityService::validateMeterNumber($this->vendor->id, $this->meter_number, $this->disco_name, $this->meter_type); 
    
            if (!$electricityValidate->status) {
                return $this->dispatch('error-toastr', ['message' => $electricityValidate->message]);
            }
    
            if ($electricityValidate->status) {
                $this->customer_name = $electricityValidate->response->name;
                $this->customer_address = $electricityValidate->response->address;
                $this->validate_action = true;
                $this->form_action = true;
                return $this->dispatch('success-toastr', ['message' => $electricityValidate->message]);
            }
        }

        if ($this->validate_action) {
            return $this->form_action = true;
        }
    }

    public function submit()
    {        

        if ($this->validate_action) {
            $electricityTransaction = ElectricityService::create($this->vendor->id, $this->disco_name, $this->meter_number, $this->meter_type, $this->amount, $this->customer_name, $this->customer_phone_number, $this->customer_address); 
            
            if (!$electricityTransaction->status) {
                $this->closeModal();
                return $this->dispatch('error-toastr', ['message' => $electricityTransaction->message]);
            }
    
            if ($electricityTransaction->status) {
                $this->closeModal();
                $this->dispatch('success-toastr', ['message' => $electricityTransaction->message]);
                session()->flash('success',  $electricityTransaction->message);
                return redirect()->route('user.transaction.electricity.receipt', $electricityTransaction->response->transaction_id);
            }
        
        }
    }

    public function beneficiary_action()
    {
        $this->beneficiary_modal = !$this->beneficiary_modal;
    }

    public function beneficiary($id)
    {
        $beneficiary = Beneficiary::find($id);
        $this->meter_number = $beneficiary->beneficiary;
        $meta = json_decode($beneficiary->meta_data);
        $this->disco_name = $meta->disco_id;
        $this->meter_type = $meta->meter_type_id;
        $this->customer_phone_number = $meta->customer_mobile_number;
        $this->beneficiary_modal = false;
        return;
    }

    public function render()
    {
        return view('livewire.pages.utility.electricity.create', [
            'electricity' => $this->vendor ? Electricity::whereVendorId($this->vendor?->id)->whereStatus(true)->get() : [],
            'beneficiaries' =>  BeneficiaryService::get('electricity')
        ]);
    }
}
