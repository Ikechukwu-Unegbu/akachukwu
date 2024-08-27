<?php

namespace App\Livewire\Pages\Utility\Airtime;

use Livewire\Component;
use App\Models\Beneficiary;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Traits\ResolvesVendorService;
use App\Services\Account\UserPinService;
use App\Services\Airtime\AirtimeService;
use App\Models\Utility\AirtimeTransaction;
use Illuminate\Validation\ValidationException;
use App\Services\Account\AccountBalanceService;
use App\Services\Beneficiary\BeneficiaryService;

class Create extends Component
{
    use ResolvesVendorService;

    public $network;
    public $vendor;
    public $amount;
    public $phone_number;
    public $beneficiary_modal = false;
    public $pin;
    public $form_action = false;
    public $validate_pin_action = false;

    public function mount()
    {
        $this->vendor = $this->getVendorService('airtime');
        $this->network = DataNetwork::whereVendorId($this->vendor?->id)->whereStatus(true)->first()?->network_id;
    }

    public function validateForm()
    {
        $this->validate([
            'network'       =>  'required|integer',
            'amount'        =>  'required|numeric',
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
        $airtimeTransaction = AirtimeService::create(
            $this->vendor->id,
            $this->network,
            $this->amount,
            $this->phone_number,
        );

        if (!$airtimeTransaction->status) {
            $this->closeModal();
            return $this->dispatch('error-toastr', ['message' => $airtimeTransaction->message]);
        }

        if ($airtimeTransaction->status) {
            $this->closeModal();
            $this->dispatch('success-toastr', ['message' => $airtimeTransaction->message]);
            session()->flash('success',  $airtimeTransaction->message);
            return redirect()->route('user.transaction.airtime.receipt', $airtimeTransaction->response->transaction_id);
        }
    }

    public function beneficiary_action()
    {
        $this->beneficiary_modal = !$this->beneficiary_modal;
    }

    public function beneficiary($id)
    {
        $beneficiary = Beneficiary::find($id);
        $meta = json_decode($beneficiary->meta_data);
        $this->network = $meta->network_id;
        $this->phone_number = $beneficiary->beneficiary;
        $this->beneficiary_modal = false;
        return;
    }
    
    public function render()
    {
        return view('livewire.pages.utility.airtime.create', [
            'networks'      =>  $this->vendor ? DataNetwork::whereVendorId($this->vendor->id)->whereStatus(true)->get() : [],
            'beneficiaries' =>  BeneficiaryService::get('airtime')
        ]);
    }
}
