<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Validate; 

class ProfileForm extends Component
{
    public $username = '';

    public $email = '';

    public $phone = '';

    public $fullname = '';

    protected $rules = [
        'name' => 'required|min:3',
        'username' => 'required|min:3',
        'email' => 'required|email',
    ];


    public function mount(User $user) // Inject User model in mount method
    {
        $this->username = $user->username;
        $this->fullname = $user->fullname;
        $this->phone = $user->phone;
        $this->email = $user->email;
    }


    public function save()
    {
     
        $this->validate();

        // Update user information based on this->user
        $this->user->name = $this->name;
        $this->user->username = $this->username;
        $this->user->email = $this->email;
        $this->user->save(); // Save changes to database

        $this->emit('profileUpdated', 'Your profile information has been saved successfully!');

        
    }



    public function render()
    {
        return view('livewire.profile.profile-form');
    }
}
