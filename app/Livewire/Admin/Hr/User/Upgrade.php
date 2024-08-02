<?php

namespace App\Livewire\Admin\Hr\User;

use App\Models\User;
use Livewire\Component;

class Upgrade extends Component
{
    public $user;
    public $level;
    public $role;

    protected $rules = [
        'level' => ['required', 'in:ordinary,reseller'],
        'role' => ['required', 'in:admin,user']
    ];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->level = $this->user->user_level;
        $this->role = $this->user->role;
        $this->authorize('view users');    
    }

    public function update()
    {
        $this->validate();
        $this->user->update([
            'user_level' => $this->level, 
            'role' => $this->role
        ]);
        $this->dispatch('success-toastr', ['message' => "User Level Upgraded Successfully"]);
        session()->flash('success', "User Level Upgraded Successfully");
        $this->redirectRoute('admin.hr.user');
    }
    
    public function render()
    {
        return view('livewire.admin.hr.user.upgrade');
    }
}
