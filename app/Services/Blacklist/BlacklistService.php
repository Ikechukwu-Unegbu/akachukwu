<?php 
namespace App\Services\Account;

use App\Models\Blacklist;

class BlacklistService{

    public function __construct()
    {
        
    }

    public function blacklist($key, $type)
    {

    }

    public function isBlacklisted(string $type, string $value): bool
    {
        return Blacklist::where('type', $type)
            ->where('value', $value)
            ->exists();
    }

    public function addToBlacklist(string $type, string $value): bool
    {
        if ($this->isBlacklisted($type, $value)) {
            return false; // Already blacklisted
        }

        Blacklist::create(['type' => $type, 'value' => $value]);
        return true;
    }


    public function removeFromBlacklist(string $type, string $value): bool
    {
        return Blacklist::where('type', $type)
            ->where('value', $value)
            ->delete();
    }


    
}

