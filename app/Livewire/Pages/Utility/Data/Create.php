<?php

namespace App\Livewire\Pages\Utility\Data;

use Livewire\Component;
use App\Models\Beneficiary;
use App\Models\Data\DataPlan;
use App\Models\Data\DataType;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;
use App\Services\Account\AccountBalanceService;
use App\Services\Data\DataService;
use App\Services\CalculateDiscount;
use Illuminate\Support\Facades\Auth;
use App\Traits\ResolvesVendorService;
use App\Services\Account\UserPinService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use App\Services\Beneficiary\BeneficiaryService;
use App\Services\Blacklist\CheckBlacklist;

class Create extends Component
{
    use ResolvesVendorService;
    public $network;
    public $vendor;
    public $dataType;
    public $phone_number;
    public $amount;
    public $plan;
    public $beneficiary_modal = false;
    public $pin = [];
    public $form_action = false;
    public $validate_pin_action = false;
    public $calculatedDiscount = 0;
    public $transaction_modal = false;
    public $transaction_status = false;
    public $transaction_link;
    public $accountBalance;

    public function mount()
    {
        $this->pin = array_fill(1, 4, '');
        $this->vendor = $this->getVendorService('data');
        $this->network = DataNetwork::whereVendorId($this->vendor?->id)->whereStatus(true)->first()?->network_id;

        $accountBalanceService = new AccountBalanceService(Auth::user());
        $this->accountBalance = $accountBalanceService->getAccountBalance();

    }

    public function updatedNetwork()
    {
        $this->plan = null;
        $this->amount = null;
        $this->dataType = null;
        $this->calculatedDiscount = 0;
    }

    public function updatedPlan()
    {
        $this->amount = DataPlan::whereVendorId($this->vendor?->id)->whereNetworkId($this->network)->whereDataId($this->plan)->first()?->amount;
        $discount = DataNetwork::whereVendorId($this->vendor?->id)->whereNetworkId($this->network)->first()->data_discount;
        $this->calculatedDiscount = CalculateDiscount::calculate((float) $this->amount, (float) $discount);
    }

    public function updatedDataType()
    {
        $this->plan = null;
        $this->amount = null;
    }

    public function validateForm()
    {
       
        $this->validate([
            'network'       =>  'required|exists:data_networks,network_id',
            'dataType'      =>  'required',
            'plan'          =>  'required',
            'phone_number'  =>  ['required', 'regex:/^0(70|80|81|90|91|80|81|70)\d{8}$/'],
        ]);

        if($this->accountBalance < $this->amount){
            $this->addError('amount', 'Your account balance is insufficient for this transaction.');
            return false;
        }
        

        return $this->form_action = true;
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

    public function selectedNetwork(DataNetwork $network)
    {
        $this->network = $network->network_id;
        $this->updatedNetwork();
        $this->updatedPlan();
    }

    public function selectedDataType(DataType $dataType)
    {
        $this->dataType = $dataType->id;
        $this->updatedDataType();
        $this->updatedPlan();
    }

    public function selectPlan(DataPlan $dataPlan)
    {
        $this->plan = $dataPlan->data_id;
        $this->updatedPlan();
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


    public function submit()
    {
        //check if blacklisted
        $isBlacklisted = CheckBlacklist::checkIfUserIsBlacklisted();
        if($isBlacklisted || !auth()->user()->hasCompletedKYC()){
    
            return redirect()->route('restrained');
        }

        $rateLimitKey = 'data-submit-' . Auth::id(); // Unique key for each user.

        if (RateLimiter::tooManyAttempts($rateLimitKey, 1)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            $this->closeModal();
            $this->transaction_modal = false;
            return $this->dispatch('error-toastr', ['message' => 'Wait a moment. Last transaction still processing.']);
        }

        RateLimiter::hit($rateLimitKey, 60); // Allow one attempt per minute.

        $dataTransaction = DataService::create(
            $this->vendor->id, 
            $this->network, 
            $this->dataType, 
            $this->plan, 
            $this->phone_number
        );

        if (!$dataTransaction->status) {
            $this->closeModal();
            $this->transaction_modal = true;
            $this->transaction_status = false;
            $this->transaction_link = "";
            return $this->dispatch('error-toastr', ['message' => $dataTransaction->message]);
        }

        if ($dataTransaction->status) {
            $this->closeModal();
            $this->transaction_status = true;
            $this->transaction_modal = true;
            $this->transaction_link = route('user.transaction.data.receipt', $dataTransaction->response->transaction_id);
            $this->dataType = "";
            $this->phone_number = "";
            $this->plan = "";
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
        $this->updatedPlan();
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
