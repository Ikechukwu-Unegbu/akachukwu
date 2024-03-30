<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Validate; 
use Illuminate\Support\Facades\Session;

class ProfileForm extends Component
{
    public $user;
    public $username = '';

    public $phone = '';

    public $name = '';

    protected $rules = [
        'name' => 'required|min:3',
        'username' => 'required|min:3',
        'phone' => 'required',
    ];


    public function mount(User $user) // Inject User model in mount method
    {
        $this->user = $user;
        $this->username = $user->username;
        $this->name = $user->name;
        $this->phone = $user->phone;
    
    }


    public function save()
    {
     
        $this->validate();
        
        // Assign other fields
        $this->user->name = $this->name;
        $this->user->username = $this->username;
        $this->user->phone = $this->phone;
        $this->user->save();
        
        Session::flash('profile_upated', 'Your profile has been updated.');
        return $this->redirect('/profile');
        
    }



    public function render()
    {
        return view('livewire.profile.profile-form');
    }
}
