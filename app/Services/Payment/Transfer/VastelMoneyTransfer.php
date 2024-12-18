<?php
namespace  App\Services\Payment\Transfer;

use App\Helpers\ApiHelper;
use App\Models\User;
use App\Helpers\GeneralHelpers;
use App\Models\MoneyTransfer;
use Illuminate\Support\Facades\DB;
use App\Services\Account\AccountBalanceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VastelMoneyTransfer{

    public $helper;
    public $accountBalanceService;

    public function __construct()
    {
        $this->helper = new GeneralHelpers();
        $this->accountBalanceService = new AccountBalanceService(Auth::user());
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
       
        DB::beginTransaction();

        try {
            // Lock the sender's account for update
            $sender = User::where('id', Auth::id())->lockForUpdate()->firstOrFail();

            // Check if the sender has sufficient balance
            if ($sender->account_balance < $data['amount']) {
                return ApiHelper::sendError([], 'Insufficient wallet balance. Please top up your account to complete the transfer.');
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
            'status'=>true ,
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
}

