<?php

namespace App\Livewire\Pages\Utility\Electricity;

use Livewire\Component;
use App\Models\Beneficiary;
use App\Models\Blacklist;
use Livewire\Attributes\Rule;
use App\Models\Data\DataVendor;
use App\Models\Utility\Electricity;
use App\Services\CalculateDiscount;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Traits\ResolvesVendorService;
use App\Services\Account\UserPinService;
use App\Models\Utility\ElectricityTransaction;
use Illuminate\Validation\ValidationException;
use App\Services\Account\AccountBalanceService;
use App\Services\Beneficiary\BeneficiaryService;
use App\Services\Blacklist\CheckBlacklist;
use App\Services\Electricity\ElectricityService;
use Illuminate\Support\Facades\RateLimiter;

class Create extends Component
{
    use ResolvesVendorService;
    public $vendor;
    public $meter_types = [1 => "PREPAID", 2 => "POSTPAID"];

    #[Rule('required')]
    public $disco_name;
    #[Rule('required|integer|in:1,2')]
    public $meter_type;
    #[Rule('required|numeric|min:0')]
    public $amount;
    #[Rule('required|numeric')]
    public $meter_number;
    #[Rule(['required', 'regex:/^0(70|80|81|90|91|80|81|70)\d{8}$/'])]
    public $customer_phone_number;

    public $customer_name;
    public $customer_address;
    public $validate_action = false;
    public $beneficiary_modal = false;

    public $pin = [];
    public $form_action = false;
    public $validate_pin_action = false;
    public $calculatedDiscount = 0;
    public $transaction_modal = false;
    public $transaction_status = false;
    public $transaction_link;

    public function mount()
    {
        $this->pin = array_fill(1, 4, '');
        $this->vendor = $this->getVendorService('electricity');
    }

    public function updatedAmount()
    {
        $discount = Electricity::whereVendorId($this->vendor->id)->first()->discount;
        $this->calculatedDiscount = CalculateDiscount::calculate((float) max(1, $this->amount), (float) $discount);
    }

    public function selectedElectricity(Electricity $electricity)
    {
        $this->disco_name = $electricity->disco_id;
        $this->updatedAmount();
        $this->updatedMeterNumber();
        $this->updatedDiscoName();
        $this->updatedMeterType();
    }

    public function updatedMeterNumber()
    {
        $this->validate_action = false;
        $this->customer_name = null;
        $this->customer_address = null;
        $this->calculatedDiscount = 0;
    }

    public function updatedDiscoName()
    {
        $this->validate_action = false;
        $this->customer_name = null;
        $this->customer_address = null;
        $this->calculatedDiscount = 0;
    }

    public function updatedMeterType()
    {
        $this->validate_action = false;
        $this->customer_name = null;
        $this->customer_address = null;
        $this->calculatedDiscount = 0;
    }

    public function closeModal()
    {
        $this->transaction_modal = false;
        $this->validate_pin_action = false;
        $this->form_action = false;
        $this->pin = array_fill(1, 4, '');
        return;
    }

    public function updatePin($index, $value)
    {
        $this->pin[$index] = $value;
    }

    public function validatePin()
    {
        if (!is_array($this->pin)) {
            $pin = (array) $this->pin;
        }

        $pin = implode('', $this->pin);

        $userPinService = UserPinService::validatePin(Auth::user(), $pin);

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

    // public function submit()
    // {
    //     if ($this->validate_action) {
    //         $electricityTransaction = ElectricityService::create($this->vendor->id, $this->disco_name, $this->meter_number, $this->meter_type, $this->amount, $this->customer_name, $this->customer_phone_number, $this->customer_address); 
            
    //         if (!$electricityTransaction->status) {
    //             $this->closeModal();
    //             $this->transaction_modal = true;
    //             $this->transaction_status = false;
    //             $this->transaction_link = "";
    //             return $this->dispatch('error-toastr', ['message' => $electricityTransaction->message]);
    //         }
    
    //         if ($electricityTransaction->status) {
    //             $this->closeModal();
    //             $this->validate_action = false;
    //             $this->transaction_status = true;
    //             $this->transaction_modal = true;
    //             $this->transaction_link = route('user.transaction.electricity.receipt', $electricityTransaction->response->transaction_id);
    //             $this->customer_name = "";
    //             $this->customer_address = "";
    //             $this->disco_name = "";
    //             $this->meter_type = "";
    //             $this->amount = "";
    //             $this->meter_number = "";
    //             $this->customer_phone_number = "";
    //             return true;
    //         }
        
    //     }
    // }


    public function submit()
    {

           //check if blacklisted
        $isBlacklisted = CheckBlacklist::checkIfUserIsBlacklisted();
        if($isBlacklisted){
    
            return redirect()->route('restrained');
        }
        
        $rateLimitKey = 'electricity-submit-' . Auth::id(); 

        
        if (RateLimiter::tooManyAttempts($rateLimitKey, 1)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            $this->closeModal();
            $this->transaction_modal = false;
            return $this->dispatch('error-toastr', ['message' => 'Wait a moment. Last transaction still processing.']);
        }

       
        RateLimiter::hit($rateLimitKey, 60);

        if ($this->validate_action) {
            $electricityTransaction = ElectricityService::create(
                $this->vendor->id,
                $this->disco_name,
                $this->meter_number,
                $this->meter_type,
                $this->amount,
                $this->customer_name,
                $this->customer_phone_number,
                $this->customer_address
            );

            if (!$electricityTransaction->status) {
                $this->closeModal();
                $this->transaction_modal = true;
                $this->transaction_status = false;
                $this->transaction_link = "";
                return $this->dispatch('error-toastr', ['message' => $electricityTransaction->message]);
            }

            if ($electricityTransaction->status) {
                $this->closeModal();
                $this->validate_action = false;
                $this->transaction_status = true;
                $this->transaction_modal = true;
                $this->transaction_link = route('user.transaction.electricity.receipt', $electricityTransaction->response->transaction_id);
                $this->customer_name = "";
                $this->customer_address = "";
                $this->disco_name = "";
                $this->meter_type = "";
                $this->amount = "";
                $this->meter_number = "";
                $this->customer_phone_number = "";
                return true;
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
