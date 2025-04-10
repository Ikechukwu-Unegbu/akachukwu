<?php

namespace App\Services\Health;

use App\Models\User;
use App\Helpers\GeneralHelpers;

class PalmPayHealthService
{
    protected ?GeneralHelpers $generalHelpers;
    public function __construct(GeneralHelpers $generalHelpers)
    {
        $this->generalHelpers = $generalHelpers;
    }

    public function hasPalyPayAccount()
    {
        return User::whereHas('virtualAccounts', function($query) {
            $query->where('bank_name', 'PalmPay');
        })->count();
    }

    public function hasNoPalyPayAccount()
    {
        return User::whereDoesntHave('virtualAccounts', function($query) {
            $query->where('bank_name',  'PalmPay');
        })->count();
    }

    public function percentageHasPalyPayAccount()
    {
        return round(($this->hasPalyPayAccount() / $this->generalHelpers->totalUsersCount()) * 100);
    }

    public function percentageHasNoPalyPayAccount()
    {
        return round(($this->hasNoPalyPayAccount() / $this->generalHelpers->totalUsersCount()) * 100);
    }
}