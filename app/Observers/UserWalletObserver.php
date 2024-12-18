<?php

namespace App\Observers;

use App\Models\User;

class UserWalletObserver
{
    public function updating(User $user)
    {
        if ($user->isDirty('account_balance') && $user->account_balance < 0) {
            throw new \Exception("Wallet balance cannot be negative. {$user->name}");
        }
    }
}
