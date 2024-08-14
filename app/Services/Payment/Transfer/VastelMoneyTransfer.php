<?php
namespace  App\Services\Payment\Transfer;

use App\Helpers\GeneralHelpers;
use App\Models\User;
use App\Services\Account\AccountBalanceService;

class VastelMoneyTransfer{

    public $helper;
    public function __construct()
    {
        $this->helper = new GeneralHelpers();
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


    public function transfer(array $data, AccountBalanceService $accountBalanceService)
    {
        $accountBalanceService->transaction($data['amount']);

        $recipientAccount = new AccountBalanceService($this->getRecipient($data['recipient']));
        $recipientAccount->updateAccountBalance($data['amount']);   
    }
}

