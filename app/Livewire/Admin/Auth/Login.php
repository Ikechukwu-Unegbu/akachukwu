<?php

namespace App\Livewire\Admin\Auth;

use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    #[Rule('required|string')]
    public $username;
    #[Rule('required|string')]
    public $password;
    #[Rule('nullable|bool')]
    public $remember_me = false;

    public function authenticate()
    {

        $this->validate();

        if (
            Auth::attempt([
                'username' => $this->username,
                'password' => $this->password
            ], $this->remember_me)

            ||

            Auth::attempt([
                'email' => $this->username,
                'password' => $this->password
            ], $this->remember_me)
        ) {
            $this->dispatch('success-toastr', ['message' => 'Login Successfully. Redirecting...']);
            return redirect()->intended(Auth::user()->dashboard());
        }

        throw ValidationException::withMessages([
            'username'   =>  __('auth.failed')
        ]);
    }


    public function render()
    {
        return view('livewire.admin.auth.login');
    }
}
