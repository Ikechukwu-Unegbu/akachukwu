<?php

namespace App\Livewire\Pages\Utility\Cable;

use Livewire\Component;
use App\Models\Beneficiary;
use App\Models\Utility\Cable;
use App\Models\Data\DataVendor;
use App\Models\Utility\CablePlan;
use App\Services\CalculateDiscount;
use App\Services\Cable\CableService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Traits\ResolvesVendorService;
use App\Models\Utility\CableTransaction;
use App\Services\Account\UserPinService;
use Illuminate\Validation\ValidationException;
use App\Services\Account\AccountBalanceService;
use App\Services\Beneficiary\BeneficiaryService;

class Create extends Component
{
    use ResolvesVendorService;
    public $vendor;
    public $cable_name;
    public $iuc_number;
    public $cable_plan;

    public $customer;
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
        $this->vendor = $this->getVendorService('cable');
    }

    public function selectedCable(Cable $cable)
    {
        $this->cable_name = $cable->cable_id;
        $this->updatedCableName();
        return true;
    }

    public function selectedPackage(CablePlan $cablePlan)
    {
        $this->cable_plan = $cablePlan->cable_plan_id;
        $this->updatedCablePlan();
        return true;
    }

    public function updatedCableName()
    {
        $this->cable_plan = null;
        $this->calculatedDiscount = 0;
    }

    public function updatedCablePlan()
    {
        $discount = Cable::whereVendorId($this->vendor?->id)->whereCableId($this->cable_name)->first()->discount;
        $amount = CablePlan::whereVendorId($this->vendor?->id)->whereCableId($this->cable_name)->whereCablePlanId($this->cable_plan)->first()->amount;
        $this->calculatedDiscount = CalculateDiscount::calculate((float) $amount, (float) $discount);
    }

    public function updatedIucNumber()
    {
        $this->validate_action = false;
        $this->customer = null;
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
        $this->validate([
            'cable_name'    =>  'required',
            'iuc_number'    =>  'required|numeric',
            'cable_plan'    =>  'required'
        ]);

        if (!$this->validate_action) {
            $cableValidate = CableService::validateIUCNumber($this->vendor->id, $this->iuc_number, $this->cable_name); 

            if (!$cableValidate->status) {
                return $this->dispatch('error-toastr', ['message' => $cableValidate->message]);
            }
    
            if ($cableValidate->status) {
                $this->customer = $cableValidate->response->name;
                $this->validate_action = true;
                $this->form_action = true;
                return $this->dispatch('success-toastr', ['message' => $cableValidate->message]);
            }

        }

        if ($this->validate_action) {
            return $this->form_action = true;
        }
    }

    public function submit()
    {
        if ($this->validate_action) {
            $cableTransaction = CableService::create($this->vendor->id, $this->cable_name, $this->cable_plan, $this->iuc_number, $this->customer);

            if (!$cableTransaction->status) {
                $this->closeModal();
                $this->transaction_modal = true;
                $this->transaction_status = false;
                $this->transaction_link = "";
                return $this->dispatch('error-toastr', ['message' => $cableTransaction->message]);
            }
    
            if ($cableTransaction->status) {
                $this->closeModal();
                $this->cable_name = "";
                $this->iuc_number = "";
                $this->cable_plan = "";
                $this->validate_action = false;
                $this->transaction_status = true;
                $this->transaction_modal = true;
                $this->transaction_link = route('user.transaction.cable.receipt', $cableTransaction->response->transaction_id);
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
        $this->iuc_number = $beneficiary->beneficiary;
        $meta = json_decode($beneficiary->meta_data);
        $this->cable_name = $meta->cable_id;
        $this->cable_plan = $meta->cable_plan_id;
        $this->beneficiary_modal = false;
        $this->updatedCablePlan();
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
