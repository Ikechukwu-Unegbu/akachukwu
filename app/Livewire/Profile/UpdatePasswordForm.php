<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UpdatePasswordForm extends Component
{

    // public $user;
    public $current_password;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'current_password' => 'required',
        'password' => 'required|min:8|confirmed',
        'password_confirmation' => 'required',
    ];

    public function submit()
    {
       
        $this->validate();
        $user = User::find(Auth::user()->id);
        $hashedPassword = $user->password;

        // Validate current password against authenticated user
        if (!Hash::check($this->current_password, $hashedPassword)) {
            $this->addError('current_password', 'The provided current password is incorrect.');
            return;
        }

        // Update user password
        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($this->password);
        $user->save();
        $this->reset(); // Reset form fields after successful update
        Session::flash('password_updated', 'Your profile has been updated.');
        return redirect('/profile');
    }

    public function render()
    {
        return view('livewire.profile.update-password-form');
    }
}
