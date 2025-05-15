<?php
namespace  App\Services\Payment\Transfer;

use App\Models\User;
use App\Helpers\ApiHelper;
use App\Models\SiteSetting;
use App\Models\MoneyTransfer;
use App\Helpers\GeneralHelpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use App\Actions\Idempotency\IdempotencyCheck;
use App\Services\Account\AccountBalanceService;

class VastelMoneyTransfer{

    public $helper;
    public $accountBalanceService;

    public function __construct()
    {
        $this->helper = new GeneralHelpers();
        $this->accountBalanceService = new AccountBalanceService(Auth::user());
    }

    private function isMoneyTransferAvailable(): bool
    {
        return (bool) optional(SiteSetting::find(1))->money_transfer_status ?? true;
    }

    public function getRecipient($recipient)
    {
        $type = $this->helper->identifyStringType($recipient);

        // Fetch the user based on the type
        if ($type === 'Email') {
            return User::where('email', $recipient)->first();
        } else {
            return User::where('username', $recipient)->first();
        }
    }


    public function transfer(array $data)
    {
        try {
            /** Random Delay */
            GeneralHelpers::randomDelay();

            /** Perform all validations */
            $validationResponse = $this->validateTransaction(Auth::user(), $data['amount']);
            if ($validationResponse) {
                return $validationResponse;
            }

            DB::beginTransaction();

            // Lock the sender's account for update
            $sender = User::where('id', Auth::id())->lockForUpdate()->firstOrFail();

            // Check if the sender has sufficient balance
            if ($sender->account_balance < $data['amount']) {
                return ApiHelper::sendError([], 'Insufficient wallet balance. Please top up your account to complete the transfer.');
            }

            /** Check for duplicate transactions using IdempotencyCheck */
            if ($this->initiateIdempotencyCheck($sender->id))  {
                return ApiHelper::sendError([], "Transaction is already pending or recently completed. Please wait!");
            }

            /**  Handle duplicate transactions */
            if ($this->initiateLimiter($sender->id)) {
                return ApiHelper::sendError([], "Please Wait a moment. Last transaction still processing.");
            }

            // Deduct the amount from the sender's balance
            $data['sender_balance_before'] = $sender->account_balance;
            $this->accountBalanceService->transaction($data['amount']);

            // Lock the recipient's account for update
            $queryRecipient = $this->getRecipient($data['recipient']);
            $recipient = User::where('id', $queryRecipient->id)->lockForUpdate()->firstOrFail();
            $data['recipient_balance_before'] = $recipient->account_balance;
            $recipientAccount = new AccountBalanceService($this->getRecipient($recipient->email));
            $recipientAccount->updateAccountBalance($data['amount'], $recipient);
            $recipient = User::where('id', $queryRecipient->id)->firstOrFail();

            $this->recordInternalTransfer($data, $recipient);

            DB::commit();

            return ApiHelper::sendResponse($recipient, "The amount of {$data['amount']} has been successfully transferred to {$recipient->name}.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            // throw $e;
            return ApiHelper::sendError([], 'Oops! Unable to complete the operation. Please try again later.');
        }
    }

    public function retry(MoneyTransfer $transfer)
    {
        try {
            DB::beginTransaction();
            $recipient = User::where('id', $transfer->receiver->id)->lockForUpdate()->firstOrFail();
            $transfer->update(['recipient_balance_before' => $recipient->account_balance]);
            $recipientAccount = (new AccountBalanceService($transfer->receiver))->updateAccountBalance($transfer->amount);
            $recipient = User::where('id', $transfer->receiver->id)->firstOrFail();
            $transfer->update(['recipient_balance_after' => $recipient->account_balance]);
            DB::commit();

            return ApiHelper::sendResponse($transfer->receiver, "The amount of {$transfer->amount} has been successfully transferred to {$transfer->receiver->name}.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return ApiHelper::sendError([], 'Oops! Unable to complete the operation. Please try again later.');
        }
    }

    public function reverse(MoneyTransfer $transfer)
    {
        try {
            DB::beginTransaction();
            $sender = User::where('id', $transfer->sender->id)->lockForUpdate()->firstOrFail();
            $recipient = User::where('id', $transfer->receiver->id)->lockForUpdate()->firstOrFail();

            $transfer->update(['recipient_balance_before' => $recipient->account_balance, 'sender_balance_before' => $sender->account_balance]);

            $recipientAccount = (new AccountBalanceService($transfer->receiver))->transaction($transfer->amount);
            $senderAccount = (new AccountBalanceService($transfer->sender))->updateAccountBalance($transfer->amount);

            $recipient = User::where('id', $transfer->receiver->id)->firstOrFail();
            $sender = User::where('id', $transfer->sender->id)->firstOrFail();

            $transfer->update(['recipient_balance_after' => $recipient->account_balance, 'sender_balance_after' => $sender->account_balance]);
            DB::commit();

            return ApiHelper::sendResponse($transfer->sender, "The amount of {$transfer->amount} has been successfully reversed to {$transfer->sender->name}.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return ApiHelper::sendError([], 'Oops! Unable to complete the operation. Please try again later.');
        }
    }

    public function reverseBankTransfer(MoneyTransfer $transfer)
    {
        try {
            DB::beginTransaction();

            $sender = User::where('id', $transfer->sender->id)->lockForUpdate()->firstOrFail();
            $transfer->update(['sender_balance_before' => $sender->account_balance]);

            $senderAccount = (new AccountBalanceService($transfer->sender))->updateAccountBalance($transfer->amount);
            $sender = User::where('id', $transfer->sender->id)->firstOrFail();

            $transfer->update(['sender_balance_after' => $sender->account_balance]);

            DB::commit();

            return ApiHelper::sendResponse($transfer->sender, "The amount of {$transfer->amount} has been successfully reversed to {$transfer->sender->name}.");
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            return ApiHelper::sendError([], 'Oops! Unable to complete the operation. Please try again later.');
        }
    }

    public function recordInternalTransfer(array $data,User $recipient)
    {
        MoneyTransfer::create([
            'user_id'=>Auth::user()->id,
            'recipient'=>$recipient->id,
            'amount'=>$data['amount'],
            'status'=>true,
            'transfer_status'=> 'successful',
            'type'=>'internal',
            'sender_balance_before' => $data['sender_balance_before'],
            'sender_balance_after' => Auth::user()->account_balance,
            'recipient_balance_before' => $data['recipient_balance_before'],
            'recipient_balance_after' => $recipient->account_balance,
            'reference_id'=>GeneralHelpers::generateUniqueRef('money_transfers')
        ]);
    }

    public function verifyRecipient($recipient)
    {
        if ($recipient && $recipient?->id === Auth::id()) {
            return ApiHelper::sendError([], 'You cannot transfer funds to your own account.');
        }

        if ($recipient && $recipient?->id !== Auth::id()) {
            return ApiHelper::sendResponse($recipient, 'Recipient found successfully. You can proceed with the transfer.');
        }

        return ApiHelper::sendError([], 'The recipient could not be found.');
    }

    private function initiateLimiter($userId) : bool
    {
        $rateLimitKey = "money-transfer-{$userId}";

        if (RateLimiter::tooManyAttempts($rateLimitKey, 1)) {
            RateLimiter::availableIn($rateLimitKey);
            return true;
        }

        RateLimiter::hit($rateLimitKey, 60);

        return false;
    }

    private function initiateIdempotencyCheck($userId)
    {
        $duplicateTransaction = IdempotencyCheck::checkDuplicateTransaction(
            MoneyTransfer::class,
            ['user_id'   => $userId],
            'transfer_status',
            ['successful', 'failed', 'pending']
        );

        return $duplicateTransaction;
    }

    private function validateTransaction(User $user, float $totalAmount)
    {
        if (!$this->isMoneyTransferAvailable()) {
            return ApiHelper::sendError([], 'Service Not Available!');
        }

        if (!GeneralHelpers::minimumTransaction($totalAmount)) {
            return ApiHelper::sendError([], "The amount is below the minimum transfer limit.");
        }

        if (!GeneralHelpers::dailyTransactionLimit(MoneyTransfer::class, $totalAmount, $user->id)) {
            return ApiHelper::sendError([], "You have exceeded your daily transaction limit.");
        }

        return null;
    }
}

