<?php 

namespace App\Services\V1\User;

use App\Models\User;

class UserProfileService{
    
    public function __construct()
    {
        
    }

    public function getUser($username)
    {
        return User::where('username', $username)->first();
    }

    public function  updateUser(array $validated, $username)
    {
        
    }
}