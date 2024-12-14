<?php

namespace App\Livewire\Component;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Validation\ValidationException;
use App\Services\Payment\Transfer\VastelMoneyTransfer;
use Illuminate\Support\Facades\Auth;

class MoneyTransferComponent extends Component
{
    protected $vastelMoneyTransfer;
    #[Validate('required|string')]
    public $username;
    public $error_msg;
    public $recipient;
    public $amount;


    public function __construct()
    {
        $this->vastelMoneyTransfer = app(VastelMoneyTransfer::class);
    }

    public function handleVerifyRecipient()
    {
        $this->validate();
        $this->error_msg = "";
        $recipient = $this->vastelMoneyTransfer->getRecipient($this->username);
        $verifyRecipient = $this->vastelMoneyTransfer->verifyRecipient($recipient);
        if ($verifyRecipient?->status) {
            $this->dispatch('success-toastr', ['message' => $verifyRecipient?->message]);
            $this->recipient = User::find($verifyRecipient->response->id);
            return;
        }
        $this->dispatch('error-toastr', ['message' => $verifyRecipient?->message]);
        return;
    }

    public function handleMoneyTransfer()
    {
        $this->validate([
            'amount' => 'required|numeric|min:50',
        ], [
            'amount.gte' => 'The amount must be above 50.',
        ]);
        
        

        $data = [
            'amount' => $this->amount,
            'recipient' => $this->recipient->email
        ];

        $handleMoneyTransfer = $this->vastelMoneyTransfer->transfer($data);

        if ($handleMoneyTransfer?->status) {
            $this->dispatch('success-toastr', ['message' => $handleMoneyTransfer?->message]);
            session()->flash('success', $handleMoneyTransfer?->message);
            $this->redirect(url()->previous());
            return;
        }

        $this->dispatch('error-toastr', ['message' => $handleMoneyTransfer?->message]);
        return true;
    }

    public function handleCloseTransferMoneyModal()
    {
        $this->recipient = "";
        $this->error_msg = "";
        return true;
    }

    public function render()
    {
        return view('livewire.component.money-transfer-component');
    }
}
