<?php

namespace App\Traits;

use App\Helpers\ApiHelper;
use App\Services\UserWatchService;

trait HandlesPostNoDebit
{
    public static function ensurePostNoDebitIsAllowed($user = null)
    {
        $user = $user ?? auth()->user();
        if (UserWatchService::blockIfPostNoDebit($user)) {
            return true;
        }

        return false;
    }
}
