<?php

namespace App\Livewire\Pages\Utility\Airtime;

use App\Http\Requests\AirtimePurchaseRequest;
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
use App\Models\Vendor;
use Illuminate\Validation\ValidationException;
use App\Services\Account\AccountBalanceService;
use App\Services\Beneficiary\BeneficiaryService;
use App\Services\Blacklist\CheckBlacklist;
use App\Services\CalculateDiscount;
use App\Traits\ResolvesAirtimeVendorService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class Create extends Component
{
    use ResolvesVendorService, ResolvesAirtimeVendorService;

    public $network;
    public $vendor;
    public $amount;
    public $phone_number;
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
        // $this->vendor = $this->getVendorService('airtime');
        $this->vendor = Vendor::where('status', true)->first();
        $this->network = DataNetwork::where('vendor_id', $this->vendor?->id)->where('airtime_status', true)->first()?->network_id;
        $accountBalanceService = new AccountBalanceService(Auth::user());
        $this->accountBalance = $accountBalanceService->getAccountBalance();

    }

    public function updatedAmount()
    {
        $discount = DataNetwork::whereVendorId($this->vendor?->id)->whereNetworkId($this->network)->first()->airtime_discount;
        $this->calculatedDiscount = CalculateDiscount::calculate((float) $this->amount, (float) $discount);
    }

    public function selectedNetwork(DataNetwork $network)
    {
        $this->network = $network->network_id;
        $this->updatedAmount();
    }

    protected function rules(): array
    {
        return (new AirtimePurchaseRequest())->rules();
    }
 

    public function validateForm()
    {
        sleep(random_int(0, 5));
        $this->validate();
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
        //rate limit
        $rateLimitKey = 'airtime-submit-' . Auth::id();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 1)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            $this->closeModal();
            $this->transaction_modal = false;
            return $this->dispatch('error-toastr', ['message' => 'Wait a moment. Last transaction still processing.']);
    
            return redirect()->back();
        }
    
        RateLimiter::hit($rateLimitKey, 60); 
        
        $checkLimit = AirtimeService::checkAirtimeLimit($this->amount);
        if ($checkLimit !== true) {
            return $this->dispatch('error-toastr', ['message' => $checkLimit->message]);
        }
      
        $airtimeTransaction = AirtimeService::create(
            $this->vendor->id,
            $this->network,
            $this->amount,
            $this->phone_number,
        );

        if (!$airtimeTransaction->status) {
            $this->closeModal();
            $this->transaction_modal = true;
            $this->transaction_status = false;
            $this->transaction_link = "";
            return $this->dispatch('error-toastr', ['message' => $airtimeTransaction->message]);
        }

        if ($airtimeTransaction->status) {
            $this->closeModal();
            $this->phone_number = "";
            $this->transaction_status = true;
            $this->transaction_modal = true;
            $this->transaction_link = route('user.transaction.airtime.receipt', $airtimeTransaction->response->transaction_id);
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
            'networks'      =>  $this->vendor ? DataNetwork::where('vendor_id', $this->vendor?->id)->where('airtime_status', true)->get() : [],
            'beneficiaries' =>  BeneficiaryService::get('airtime')
        ]);
    }
}
