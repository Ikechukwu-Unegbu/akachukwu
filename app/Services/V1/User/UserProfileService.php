<?php 

namespace App\Services\V1\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserProfileService{
    
    public function __construct()
    {
        
    }

    public function getUser($username)
    {
        return User::where('username', $username)->first();
    }

    public function  updateUser(array $validated)
    {
        $user = User::where('username', Auth::user()->username)->first();

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->phone = $validated['phone'];
        $user->save();
        return $user;
    }
}