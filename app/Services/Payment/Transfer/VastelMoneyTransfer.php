<?php

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


    public function transfer($recipient, $amount, $accountBalanceService)
    {
        $accountBalanceService->transaction($amount);

        $recipientAccount = new AccountBalanceService($this->getRecipient($recipient));
        $recipientAccount->updateAccountBalance($amount);   
    }
}

