<?php

namespace App\Livewire\Admin\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Rule;

class Register extends Component
{
    #[Rule('required|string')]
    public $name;
    #[Rule('required|email|unique:users,email')]
    public $email;
    #[Rule('required|string|unique:users,username')]
    public $username;
    #[Rule('required|min:6|max:15')]
    public $password;
    #[Rule('required|same:password')]
    public $password_confirmation;

    public function register()
    {
        $this->validate();

        User::create([
            'name'      => trim($this->name),
            'username'  => trim($this->username),
            'email'     => trim($this->email),
            'password'  => bcrypt($this->password),
            'role'      => 'admin'
        ]);

        $this->dispatch('success-toastr', ['message' => 'Account Created Successfully']);
        session()->flash('success', 'Account Created Successfully. Proceed to login');
        return redirect()->to(route('admin.auth.login'));
    }


    public function render()
    {
        return view('livewire.admin.auth.register');
    }
}
