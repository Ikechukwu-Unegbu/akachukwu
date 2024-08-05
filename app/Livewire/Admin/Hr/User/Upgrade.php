<?php

namespace App\Livewire\Admin\Hr\User;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Upgrade extends Component
{
    public $user;
    public $level;
    public $role;
    public $assign_role;
    public $assign_role_action = false;

    protected $rules = [
        'level' => ['required', 'in:ordinary,reseller'],
        'role' => ['required', 'in:admin,user']
    ];

    public function updatedRole()
    {
        if ($this->role === 'admin') {
            return $this->assign_role_action = true;
        }

        return $this->assign_role_action = false;
    }

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

        if ($this->role === 'admin') {
            $model_role = Role::find($this->assign_role);
            $this->user->assignRole($model_role->name);
            $permissions = $model_role->permissions->pluck('name', 'name')->toArray();
            $this->user->givePermissionTo($permissions);
        }

        $this->dispatch('success-toastr', ['message' => "User Level Upgraded Successfully"]);
        session()->flash('success', "User Level Upgraded Successfully");
        $this->redirectRoute('admin.hr.user');
    }
    
    public function render()
    {
        return view('livewire.admin.hr.user.upgrade', [
            'roles'  => Role::get()
        ]);
    }
}
