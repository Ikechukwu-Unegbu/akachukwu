<?php

namespace App\Livewire\Pages\Utility\Data;

use Livewire\Component;
use App\Models\Beneficiary;
use App\Models\Data\DataPlan;
use App\Models\Data\DataType;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;
use App\Services\Data\DataService;
use Illuminate\Support\Facades\Auth;
use App\Services\Account\UserPinService;
use Illuminate\Validation\ValidationException;
use App\Services\Beneficiary\BeneficiaryService;

class Create extends Component
{
    public $network;
    public $vendor;
    public $dataType;
    public $phone_number;
    public $amount;
    public $plan;
    public $beneficiary_modal = false;
    public $pin;
    public $form_action = false;
    public $validate_pin_action = false;

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

    public function validateForm()
    {
        $this->validate([
            'network'       =>  'required|integer',
            'dataType'      =>  'required|integer',
            'plan'          =>  'required|integer',
            'phone_number'  =>  ['required', 'regex:/^0(70|80|81|90|91|80|81|70)\d{8}$/'],
        ]);

        return $this->form_action = true;
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

    public function submit()
    {
        $dataTransaction = DataService::create(
            $this->vendor->id, 
            $this->network, 
            $this->dataType, 
            $this->plan, 
            $this->phone_number
        );

        if (!$dataTransaction->status) {
            $this->closeModal();
            return $this->dispatch('error-toastr', ['message' => $dataTransaction->message]);
        }

        if ($dataTransaction->status) {
            $this->closeModal();
            $this->dispatch('success-toastr', ['message' => $dataTransaction->message]);
            session()->flash('success',  $dataTransaction->message);
            return redirect()->route('user.transaction.data.receipt', $dataTransaction->response->transaction_id);
        }
    }

    public function beneficiary_action()
    {
        $this->beneficiary_modal = !$this->beneficiary_modal;
    }

    public function beneficiary($id)
    {
        $beneficiary = Beneficiary::find($id);
        $this->phone_number = $beneficiary->beneficiary;
        $meta = json_decode($beneficiary->meta_data);
        $this->network = $meta->network_id;
        $this->dataType = $meta->type_id;
        $plan = DataPlan::whereVendorId($this->vendor->id)->whereTypeId($meta->type_id)->whereDataId($meta->data_id)->first();
        $this->plan = $plan?->data_id;
        $this->amount = $plan?->amount;
        $this->beneficiary_modal = false;
        return;
    }

    public function render()
    {
        return view('livewire.pages.utility.data.create', [
            'networks'      =>  $this->vendor ? DataNetwork::whereVendorId($this->vendor->id)->whereStatus(true)->get() : [],
            'dataTypes'     =>  $this->vendor && $this->network ? DataType::whereVendorId($this->vendor->id)->whereNetworkId($this->network)->whereStatus(true)->get() : [],
            'plans'         =>  $this->vendor && $this->network && $this->dataType ? DataPlan::with('type')->whereVendorId($this->vendor->id)->whereNetworkId($this->network)->whereTypeId($this->dataType)->whereStatus(true)->get() : [],
            'beneficiaries' =>  BeneficiaryService::get('data')
        ]);
    }
}
