<?php
namespace  App\Services\Payment\Transfer;

use App\Helpers\ApiHelper;
use App\Models\User;
use App\Helpers\GeneralHelpers;
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
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Lock the sender's account for update
            $sender = User::where('id', Auth::id())->lockForUpdate()->firstOrFail();

            // Check if the sender has sufficient balance
            if ($sender->account_balance < $data['amount']) {
                return ApiHelper::sendError([], 'Insufficient wallet balance. Please top up your account to complete the transfer.');
            }

            // Deduct the amount from the sender's balance
            $this->accountBalanceService->transaction($data['amount']);

            // Lock the recipient's account for update
            $recipient = User::where('id', $data['recipient'])->lockForUpdate()->firstOrFail();
            $recipientAccount = new AccountBalanceService($this->getRecipient($recipient->email));
            $recipientAccount->updateAccountBalance($data['amount']);
            
            DB::commit();

            return ApiHelper::sendResponse($recipient, 'Transaction was successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return ApiHelper::sendError([], 'Oops! Unable to complete the operation. Please try again later.');
        }
    }
}

