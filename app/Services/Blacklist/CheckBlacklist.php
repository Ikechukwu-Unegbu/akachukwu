<?php

namespace App\Services\Blacklist;

use App\Models\User;
use App\Models\Blacklist;
use Illuminate\Support\Facades\Auth;

class CheckBlacklist
{
    public static function checkIfUserIsBlacklisted()
    {
     
        $user = Auth::user();

        if (!$user) {
            return false; 
        }

        $fieldsToCheck = [
            $user->id,
            $user->email,
            $user->bvn,
            $user->nin,
        ];

        $isBlacklisted = Blacklist::whereIn('value', $fieldsToCheck)->exists();

        return $isBlacklisted;
    }
}
