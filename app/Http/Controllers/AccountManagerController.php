<?php

namespace App\Http\Controllers;

use App\Services\V1\User\UserProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AccountManagerController extends Controller
{
    public function __construct(public UserProfileService $profileService )
    {
        
    }

    public function deleteMyAccount(Request $request)
    {
        $request->validate([
            "password"=>"required|string"
        ]);

        $this->profileService->deleteUserProfile($request->all(), $request);
        Session::flash('success', "Sad to see you go!");
        return redirect()->route('register');
    }
}
