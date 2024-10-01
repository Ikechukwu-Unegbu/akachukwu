<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{
    public function index()
    {
        return view('pages.settings.index');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);
        $user = User::find(Auth::user()->id);
        $hashedPassword = $user->password;

       
        if (!Hash::check($request->current_password, $hashedPassword)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

   
        $user->password = Hash::make($request->new_password);
        $user->save();
  
        Session::flash('success', 'Your profile has been updated.');
        return redirect()->back();
    }

    public function referral()
    {
        return view('pages.settings.referral');
    }

    public function support()
    {
        return view('pages.settings.support');
    }

    public function credentials()
    {
        return view('pages.settings.credentials');
    }
}
