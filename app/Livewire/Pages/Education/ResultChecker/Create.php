<?php

namespace App\Livewire\Pages\Education\ResultChecker;

use App\Models\Vendor;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Education\ResultChecker;
use App\Services\Account\UserPinService;
use App\Services\Education\ResultCheckerService;
use Illuminate\Validation\ValidationException;

class Create extends Component
{
    public $vendor;
    public $exam_name;
    public $quantity = 1;
    public $amount;
    public $pin;
    public $form_action = false;
    public $validate_pin_action = false;

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
        
        $resultCheckerService = ResultCheckerService::create($this->vendor->id, $this->exam_name, $this->quantity);

        if (!$resultCheckerService->status) {
            $this->closeModal();
            return $this->dispatch('error-toastr', ['message' => $resultCheckerService->message]);
        }

        if ($resultCheckerService->status) {
            $this->closeModal();
            $this->dispatch('success-toastr', ['message' => $resultCheckerService->message]);
            session()->flash('success',  $resultCheckerService->message);
            return redirect()->route('user.transaction.education.receipt', $resultCheckerService->response->transaction_id);
        }
    }

    public function render()
    {
        return view('livewire.pages.education.result-checker.create', [
            'resultCheckers' => ResultChecker::whereVendorId($this->vendor->id)->whereStatus(true)->get()
        ]);
    }
}
