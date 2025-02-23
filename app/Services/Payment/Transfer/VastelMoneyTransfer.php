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
        if (!$this->isMoneyTransferAvailable()) {
            return ApiHelper::sendError([], 'Service Not Available!');
        }
        
        DB::beginTransaction();

        try {
            // Lock the sender's account for update
            $sender = User::where('id', Auth::id())->lockForUpdate()->firstOrFail();

            // Check if the sender has sufficient balance
            if ($sender->account_balance < $data['amount']) {
                return ApiHelper::sendError([], 'Insufficient wallet balance. Please top up your account to complete the transfer.');
            }

            /** Check for duplicate transactions using IdempotencyCheck */
            if (self::initiateIdempotencyCheck($sender->id))  {
                return ApiHelper::sendError([], "Transaction is already pending or recently completed. Please wait!");
            }

            /**  Handle duplicate transactions */
            if (self::initiateLimiter($sender->id)) {
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
            return;
        }

        if ($recipient && $recipient?->id !== Auth::id()) {
            return ApiHelper::sendResponse($recipient, 'Recipient found successfully. You can proceed with the transfer.');
        }

        return ApiHelper::sendError([], 'The recipient could not be found.');
    }

    protected static function initiateLimiter($userId) : bool
    {        
        $rateLimitKey = "money-transfer-{$userId}";

        if (RateLimiter::tooManyAttempts($rateLimitKey, 1)) {
            RateLimiter::availableIn($rateLimitKey);           
            return true;
        }
    
        RateLimiter::hit($rateLimitKey, 60);

        return false;
    }

    protected static function initiateIdempotencyCheck($userId)
    {
        $duplicateTransaction = IdempotencyCheck::checkDuplicateTransaction(
            MoneyTransfer::class, 
            ['user_id'   => $userId],
            'transfer_status',
            ['successful', 'failed', 'pending']
        );
  
        return $duplicateTransaction;
    }
}

