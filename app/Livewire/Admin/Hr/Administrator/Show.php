<?php

namespace App\Livewire\Admin\Hr\Administrator;

use App\Models\User;
use Livewire\Component;

class Show extends Component
{
    public User $user;
    
    public function render()
    {
        return view('livewire.admin.hr.administrator.show');
    }
}