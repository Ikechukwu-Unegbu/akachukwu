<?php

namespace App\Livewire\Admin\Auth;

use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    #[Rule('required|email')]
    public $email;
    #[Rule('required|string')]
    public $password;
    #[Rule('nullable|bool')]
    public $remember_me = false;

    public function authenticate()
    {

        $validated = $this->validate();

        if (Auth::attempt([
            'email' => $this->email, 
            'password' => $this->password
            ], $this->remember_me))
            
            return redirect()->intended(Auth::user()->dashboard());

        throw ValidationException::withMessages([
            'email'   =>  __('auth.failed')
        ]);
    }


    public function render()
    {
        return view('livewire.admin.auth.login');
    }
}
