<?php

namespace App\Livewire\Pages\Education\ResultChecker;

use App\Models\Vendor;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Education\ResultChecker;
use App\Services\Account\UserPinService;
use App\Services\Blacklist\CheckBlacklist;
use App\Services\Education\ResultCheckerService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;


class Create extends Component
{
    public $vendor;
    public $exam_name;
    public $quantity = 1;
    public $amount;
    public $pin = [];
    public $form_action = false;
    public $validate_pin_action = false;
    public $transaction_modal = false;
    public $transaction_status = false;
    public $transaction_link;

    public function mount()
    {
        $this->vendor = Vendor::where('name', 'VTPASS')->first();
    }

    public function validateForm()
    {
        $this->validate([
            'exam_name'   =>  'required|string',
            'amount'      =>  'required|numeric',
            'quantity'    =>  'required|numeric',
        ]);

        return $this->form_action = true;
    }

    public function updatedExamName()
    {
        $this->quantity = max(1, $this->quantity);
    
        $checker = ResultChecker::where('vendor_id', $this->vendor->id)
                                ->where('name', $this->exam_name)
                                ->first();
    
        if ($checker) {
            $amount = $checker->amount;
            $totalAmount = $amount * $this->quantity;
            $this->amount = number_format($totalAmount, 2, '.', '');
        }
    
        return;
    }

    public function updatedQuantity()
    {
       $this->updatedExamName();
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
        if (!is_array($this->pin)) $pin = (array) $this->pin;
        $pin = implode('', $this->pin);

        $userPinService = UserPinService::validatePin(Auth::user(), $pin);

        if (!$userPinService) {
            $this->pin = array_fill(1, 4, '');
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
        $rateLimitKey = 'result-checker-submit-' . Auth::id(); // Unique key for throttling by user ID.

        // Check if the user has exceeded the rate limit.
        if (RateLimiter::tooManyAttempts($rateLimitKey, 1)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            $this->closeModal();
            $this->transaction_modal = false;
            return $this->dispatch('error-toastr', ['message' => 'Wait a moment. Last transaction still processing.']);
        }

        // Record a new attempt with a 60-second expiry.
        RateLimiter::hit($rateLimitKey, 60);

        // Proceed with the transaction.
        $resultCheckerService = ResultCheckerService::create($this->vendor->id, $this->exam_name, $this->quantity);

        if (!$resultCheckerService->status) {
            $this->closeModal();
            $this->transaction_modal = true;
            $this->transaction_status = false;
            $this->transaction_link = "";
            return $this->dispatch('error-toastr', ['message' => $resultCheckerService->message]);
        }

        if ($resultCheckerService->status) {
            $this->closeModal();
            $this->quantity = 1;
            $this->transaction_status = true;
            $this->transaction_modal = true;
            $this->transaction_link = route('user.transaction.education.receipt', $resultCheckerService->response->transaction_id);
        }
    }


    public function render()
    {
        return view('livewire.pages.education.result-checker.create', [
            'resultCheckers' => ResultChecker::whereVendorId($this->vendor->id)->whereStatus(true)->get()
        ]);
    }
}
