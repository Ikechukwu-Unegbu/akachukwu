<?php

namespace App\Services\Health;

use App\Models\User;
use App\Helpers\GeneralHelpers;

class KycHealthService
{
    protected ?GeneralHelpers $generalHelpers;
    public function __construct(GeneralHelpers $generalHelpers)
    {
        $this->generalHelpers = $generalHelpers;
    }
    public function hasCompletedKyc()
    {
        return User::isKYCValidated()->count();
    }

    public function hasNotCompletedKyc()
    {
        return User::isKYCNotValidated()->count();
    }

    public function percentageKycCompleted()
    {
        return round(($this->hasCompletedKyc() / $this->generalHelpers->totalUsersCount()) * 100);
    }

    public function percentageKycNotCompleted()
    {
        return round(($this->hasNotCompletedKyc() / $this->generalHelpers->totalUsersCount()) * 100);
    }
}