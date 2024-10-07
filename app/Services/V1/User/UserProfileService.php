<?php 

namespace App\Services\V1\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserProfileService{
    
    public function __construct()
    {
        
    }

    public function getUser($input)
    {
        return User::where(function($query) use ($input) {
            $query->where('username', $input)
                  ->orWhere('email', $input);
        })->first();
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

    public function deleteUserProfile($data, $request)
    {
        $user = User::find(Auth::user()->id);
        $user->soft_deleted_by = $user->id;
        $user->save();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
   
    }
}