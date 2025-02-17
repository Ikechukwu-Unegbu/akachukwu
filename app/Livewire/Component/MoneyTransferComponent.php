<?php

namespace App\Livewire\Component;

use App\Models\User;
use Livewire\Component;
use App\Models\PalmPayBank;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use App\Services\Money\PalmPayService;
use App\Services\Account\UserPinService;
use Illuminate\Validation\ValidationException;
use App\Services\Payment\Transfer\VastelMoneyTransfer;
use App\Http\Requests\MoneyTransfer\InitiateTransferRequest;

class MoneyTransferComponent extends Component
{
    protected $vastelMoneyTransfer;
    #[Validate('required|string')]
    public $username;
    public $error_msg;
    public $recipient;
    public $amount;
    public $bank;
    public $bankDetails;
    public $account_number = 8152522525;
    public $account_name;
    public $initiateBankTransfer = true;
    public $account_verification = false;
    public $initiateTransferAmount = false;
    public $initiatePreviewTransaction = false;
    public $initiateTransactionPin = false;
    public $remark;
    public $transactionFee = 10.0;
    public $pin = [];
    public $handleMethodAction = ['method' => 'handleVerifyAccountNumber', 'action' => 'Proceed'];
    public $transferMethods = [1 => 'Vastel User', 2 => 'Bank Transfer'];
    public $transferMethod;
    public $transactionStatus = false;
    public $transactionStatusModal = false;
    public $vastelTransactionStatus = false;

    public function __construct()
    {
        $this->vastelMoneyTransfer = app(VastelMoneyTransfer::class);
    }

    public function mount()
    {
        $this->pin = array_fill(1, 4, '');
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
            $this->vastelTransactionStatus = true;
            $this->redirect(url()->previous());
            return;
        }
        $this->vastelTransactionStatus = false;
        $this->dispatch('error-toastr', ['message' => $handleMoneyTransfer?->message]);
        return true;
    }

    public function handleCloseTransferMoneyModal()
    {
        $this->recipient = "";
        $this->error_msg = "";
        $this->transferMethod = "";
        $this->transactionStatus = false;
        $this->transactionStatusModal = false;
        return true;
    }

    public function selectedTransferMethod($method)
    {
        $this->transferMethod = $method;
        return true;
    }

    public function selectedBank($id)
    {
        $this->handleMethodAction = ['method' => 'handleVerifyAccountNumber', 'action' => 'Proceed'];
        $this->account_verification = false;
        $this->account_name = "";
        $this->bank = $id;
        $this->bankDetails = PalmPayBank::find($this->bank);
    }

    public function updatedSearch($id)
    {
        $this->handleMethodAction = ['method' => 'handleVerifyAccountNumber', 'action' => 'Proceed'];
        $this->bank = "";
    }

    public function handleVerifyAccountNumber()
    {
        $this->queryBankAccount();
        return;
    }

    protected function queryBankAccount()
    {
        $this->validate([
            'account_number' => ['required', 'numeric', 'digits:10'],
            'bank'           =>  ['required', 'exists:palm_pay_banks,id']
        ]);

        $this->account_verification = false;
        $this->bankDetails = PalmPayBank::find($this->bank);
        $palmPayService = PalmPayService::queryBankAccount($this->bankDetails->code, $this->account_number);

        if (!$palmPayService->status) {
            $this->dispatch('error-toastr', ['message' => $palmPayService?->message]);
            return;
        }

        $this->handleMethodAction = ['method' => 'handleInitiateTransferAmount', 'action' => 'Proceed'];
        $this->account_name = $palmPayService->response->accountName;
        $this->dispatch('success-toastr', ['message' => $palmPayService?->message]);

        return;
    }

    public function handleInitiateTransferAmount()
    {
        $this->initiateTransferAmount = true;
        $this->initiateBankTransfer = false;
        $this->handleMethodAction = ['method' => 'handleInitiateTransferDetail', 'action' => 'Confirm'];
    }

    public function handleInitiateTransferDetail()
    {
        $this->validate([
            'amount' => [
                'required',
                'numeric',
                'min:100',
                function ($attribute, $value, $fail) {
                    $user = auth()->user();
                    if ($value > $user->account_balance) {
                        $fail("The {$attribute} exceeds your available balance of ₦" . number_format($user->account_balance, 2));
                    }
                }
            ],
            'remark' => 'nullable|string|min:5|max:50'
        ]);

        $this->handleMethodAction = ['method' => 'handleInitiatePreviewTransaction', 'action' => 'Pay'];
        $this->initiateTransferAmount = false;
        $this->initiatePreviewTransaction = true;
    }

    public function handleInitiatePreviewTransaction()
    {
        $this->handleMethodAction = ['method' => 'handleInitiateTransactionPin', 'action' => 'Pay'];
        $this->initiatePreviewTransaction = false;
        $this->initiateTransactionPin = true;
    }

    public function updatePin($index, $value)
    {
        $this->pin[$index] = $value;
    }

    public function handleInitiateTransactionPin()
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

        return $this->handleBankTransferProcess();
    }

    protected function handleBankTransferProcess()
    {
        $process = PalmPayService::processBankTransfer(
            $this->account_name,
            $this->account_number,
            $this->bankDetails->code,
            $this->bankDetails->id,
            $this->amount+$this->transactionFee,
            $this->transactionFee,
            $this->remark,
            auth()->id()
        );
        
        if ($process?->status) {
            $this->bank = "";
            $this->dispatch('success-toastr', ['message' => $process?->message]);
            session()->flash('success', $process?->message);
            $this->initiateTransactionPin = false;
            $this->transactionStatusModal = true;
            $this->transactionStatus = true;
            sleep(5);
            return;
            // return $this->redirect(url()->previous());            
        }
        
        $this->initiateTransactionPin = false;
        $this->transactionStatusModal = true;
        $this->transactionStatus = false;
        session()->flash('error', $process?->message);
        $this->dispatch('error-toastr', ['message' => $process?->message]);
        sleep(2);
        return $this->redirect(url()->previous());
    }

    public function render()
    {
        return view('livewire.component.money-transfer-component', [
            'banks' => PalmPayBank::where('status', true)->orderBy('name')->get()
        ]);
    }
}
