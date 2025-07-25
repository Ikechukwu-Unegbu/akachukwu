<?php

namespace App\Livewire\Component;

use App\Models\Bank;
use App\Models\User;
use Livewire\Component;
use App\Models\SiteSetting;
use App\Models\MoneyTransfer;
use App\Helpers\GeneralHelpers;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use App\Services\Money\PalmPayService;
use App\Services\Account\UserPinService;
use Illuminate\Validation\ValidationException;
use App\Services\Money\PalmPayMoneyTransferService;
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
    public $account_number;
    public $account_name;
    public $initiateBankTransfer = true;
    public $account_verification = false;
    public $initiateTransferAmount = false;
    public $initiatePreviewTransaction = false;
    public $initiateTransactionPin = false;
    public $remark;
    public $transactionFee;
    public $pin = [];
    public $handleMethodAction = ['method' => 'handleVerifyAccountNumber', 'action' => 'Proceed'];
    public $transferMethods = [1 => 'Vastel User', 2 => 'Bank Transfer'];
    public $transferMethod;
    public $vastelTransactionStatus = false;
    public $transactionStatus = false;
    public $transactionStatusModal = false;
    public $settings;
    /**
     * Constructor to initialize the VastelMoneyTransfer service.
     */
    public function __construct()
    {
        $this->vastelMoneyTransfer = app(VastelMoneyTransfer::class);
    }

    /**
     * Mount the component and initialize default values.
     */
    public function mount()
    {
        $this->pin = array_fill(1, 4, '');
        $this->transactionFee = optional(SiteSetting::find(1))->transfer_charges ?? 50;
        $this->settings =  SiteSetting::find(1);
    }

    /**
     * Verifies the recipient's username or email using the VastelMoneyTransfer service.
     */
    public function handleVerifyRecipient()
    {
        if (!$this->settings->money_transfer_status) {
            $this->dispatch('error-toastr', ['message' => 'Transfer is not available. Please try again later.']);
            return;
        }

        $this->validate();
        $this->error_msg = "";
        $recipient = $this->vastelMoneyTransfer->getRecipient($this->username);
        $verifyRecipient = $this->vastelMoneyTransfer->verifyRecipient($recipient, auth()->id());
        if ($verifyRecipient?->status) {
            $this->dispatch('success-toastr', ['message' => $verifyRecipient?->message]);
            $this->recipient = User::find($verifyRecipient->response->id);
            return;
        }
        $this->dispatch('error-toastr', ['message' => $verifyRecipient?->message]);
        return;
    }

    /**
     * Handles the money transfer process, ensuring validation and transaction execution.
     */
    public function handleMoneyTransfer()
    {
        $this->validate([
            'amount' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                function ($attribute, $value, $fail) {
                    $user = auth()->user();
                    if ($value > $user->account_balance) {
                        $fail("The {$attribute} exceeds your available balance of ₦" . number_format($user->account_balance, 2));
                    }

                    if (!$this->settings->money_transfer_status) {
                        $fail("Transfer is not available. Please try again later.");
                    }

                    if (!GeneralHelpers::minimumTransaction($value)) {
                        $fail("The minimum transfer amount is ₦" . number_format($this->settings->minimum_transfer, 2));
                    }

                    $singleLimit = GeneralHelpers::singleTransactionLimit($value, $user->id);
                    if (!$singleLimit->status) {
                        $fail("The maximum single transfer limit is ₦" . number_format($singleLimit->limit, 2));
                    }

                    if (!GeneralHelpers::dailyTransactionLimit(MoneyTransfer::class, $value, Auth::id())) {
                        $fail("You have exceeded your daily transaction limit.");
                    }
                }
            ],
        ]);

        $data = [
            'amount' => $this->amount,
            'recipient' => $this->recipient->email
        ];

        $handleMoneyTransfer = $this->vastelMoneyTransfer->transfer($data);

        if ($handleMoneyTransfer?->status) {
            $this->vastelTransactionStatus = true;
            // dd($this->vastelTransactionStatus);
            $this->dispatch('success-toastr', ['message' => $handleMoneyTransfer?->message]);
            session()->flash('success', $handleMoneyTransfer?->message);
            $this->redirect(url()->previous());
            return;
        }
        $this->vastelTransactionStatus = false;
        $this->dispatch('error-toastr', ['message' => $handleMoneyTransfer?->message]);
        return true;
    }

    /**
     * Resets transfer-related properties and closes the transaction modal.
     */
    public function handleCloseTransferMoneyModal()
    {
        $this->recipient = "";
        $this->error_msg = "";
        $this->transferMethod = false;
        $this->transactionStatus = false;
        $this->transactionStatusModal = false;
        $this->account_number = '';
        $this->account_name = '';
        $this->initiateBankTransfer = true;
        $this->initiatePreviewTransaction = false;
        $this->initiateTransactionPin = false;
        $this->bankDetails = '';
        $this->bank = '';
        $this->transferMethod = '';
        $this->amount = (float) '';
        $this->remark = '';
        $this->handleMethodAction = ['method' => 'handleVerifyAccountNumber', 'action' => 'Proceed'];
        $this->initiateTransferAmount = false;
        $this->pin = array_fill(1, 4, '');
        return true;
    }

    /**
     * Sets the selected transfer method.
     *
     * @param int $method
     */
    public function selectedTransferMethod($method)
    {
        $this->transferMethod = $method;
        return true;
    }

    /**
     * Sets the selected bank and resets verification status.
     *
     * @param int $id
     */
    public function selectedBank($id)
    {
        $this->handleMethodAction = ['method' => 'handleVerifyAccountNumber', 'action' => 'Proceed'];
        $this->account_verification = false;
        $this->account_name = "";
        $this->bank = $id;
        $this->bankDetails = Bank::find($this->bank);
    }

    public function updatedSearch($id)
    {
        $this->handleMethodAction = ['method' => 'handleVerifyAccountNumber', 'action' => 'Proceed'];
        $this->bank = "";
    }

    /**
     * Handles account number verification.
     */
    public function handleVerifyAccountNumber()
    {
        $this->queryBankAccount();
        return;
    }

    /**
     * Queries the bank account details using PalmPayService.
     */
    protected function queryBankAccount()
    {
        $this->validate([
            'account_number' => ['required', 'numeric', 'digits:10'],
            'bank'           =>  ['required', 'exists:banks,id']
        ]);

        if (!$this->settings->bank_transfer_status) {
            $this->dispatch('error-toastr', ['message' => 'Bank transfer is not available. Please try again later.']);
            return;
        }

        if (!auth()->user()->isKycDone()) {
            $this->dispatch('error-toastr', ['message' => 'To use service, please complete your KYC by providing your BVN or NIN.']);
            session()->flash('error', 'To use service, please complete your KYC by providing your BVN or NIN.');
            return $this->redirectRoute('restrained');
        }

        $this->account_verification = false;
        $this->bankDetails = Bank::find($this->bank);
        $palmPayService = PalmPayMoneyTransferService::queryBankAccount($this->bankDetails->code, $this->account_number);

        if (!$palmPayService->status) {
            $this->dispatch('error-toastr', ['message' => $palmPayService?->message]);
            return;
        }

        $this->handleMethodAction = ['method' => 'handleInitiateTransferAmount', 'action' => 'Proceed'];
        $this->account_name = $palmPayService->response->accountName;
        $this->dispatch('success-toastr', ['message' => $palmPayService?->message]);

        return;
    }

    /**
     * Updates Form action to use handleInitiateTransferDetail
     */
    public function handleInitiateTransferAmount()
    {
        $this->initiateTransferAmount = true;
        $this->initiateBankTransfer = false;
        $this->handleMethodAction = ['method' => 'handleInitiateTransferDetail', 'action' => 'Confirm'];
    }

    /**
     * Process and validates amount & remark
     */
    public function handleInitiateTransferDetail()
    {
        $this->validate([
            'amount' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:100',
                function ($attribute, $value, $fail) {
                    $user = auth()->user();
                    if ($value > $user->account_balance) {
                        $fail("The {$attribute} exceeds your available balance of ₦" . number_format($user->account_balance, 2));
                    }

                    if (!$this->settings->bank_transfer_status) {
                        $fail("Bank transfer is not available. Please try again later.");
                    }

                    if (!GeneralHelpers::minimumTransaction($value)) {
                        $fail("The minimum transfer amount is ₦" . number_format($this->settings->minimum_transfer, 2));
                    }
                    $singleLimit = GeneralHelpers::singleTransactionLimit($value, $user->id);
                    if (!$singleLimit->status) {
                        $fail("The maximum single transfer limit is ₦" . number_format($singleLimit->limit, 2));
                    }

                    if (!GeneralHelpers::dailyTransactionLimit(MoneyTransfer::class, $value, Auth::id())) {
                        $fail("You have exceeded your daily transaction limit.");
                    }
                }
            ],
            'remark' => 'nullable|string|min:5|max:50'
        ]);

        $this->handleMethodAction = ['method' => 'handleInitiatePreviewTransaction', 'action' => 'Pay'];
        $this->initiateTransferAmount = false;
        $this->initiatePreviewTransaction = true;
    }

    /**
     * Updates form action to display or preview transaction before sending
     */
    public function handleInitiatePreviewTransaction()
    {
        $this->handleMethodAction = ['method' => 'handleInitiateTransactionPin', 'action' => 'Pay'];
        $this->initiatePreviewTransaction = false;
        $this->initiateTransactionPin = true;
    }

    /**
     * Updates inputed PIN.
     */
    public function updatePin($index, $value)
    {
        $this->pin[$index] = $value;
    }

    /**
     * Validates and processes the transaction PIN.
     */
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

     /**
     * Handles the bank transfer process using PalmPayService.
     */
    protected function handleBankTransferProcess()
    {
        $this->validate([
            'amount' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
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

        $process = PalmPayMoneyTransferService::processBankTransfer(
            $this->account_name,
            $this->account_number,
            $this->bankDetails->code,
            $this->bankDetails->id,
            $this->amount,
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
            return $this->redirect(url()->previous());
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
            'banks' => Bank::where('type', 'palmpay')->where('status', true)->orderBy('name')->get()
        ]);
    }
}
