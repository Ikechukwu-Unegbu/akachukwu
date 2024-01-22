<?php

namespace App\Services\Account;

use Illuminate\Foundation\Auth\User;

class AccountBalanceService 
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getAccountBalance()
    {
        return $this->user->account_balance;
    }

    public function updateAccountBalance($amount)
    {
        $this->user->setAccountBalance($amount);
        return true;
    }

    public function transaction($amount)
    {
        $this->user->setTransaction($amount);
        return true;
    }

    public function verifyAccountBalance($amount) : bool
    {
        if ($this->getAccountBalance() >= $amount) return true;

        return false;
    }

}