<?php

namespace App\Livewire\Admin\Hr\Administrator;

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
        $this->assign_role_action = ($this->role === 'admin') ? true : false;
        $this->assign_role = $this->user->roles()->first()?->id;
        $this->authorize('view administrators');
    }

    public function update()
    {
        $this->validate();
        $this->user->update([
            'user_level' => $this->level, 
            'role' => $this->role
        ]);
            
        if ($this->role === 'user') {
            if ($this->user->roles()->count()) {
                foreach ($this->user->roles as $role)  $this->user->removeRole($role->name);
                foreach ($this->user->permissions as $permission) $this->user->revokePermissionTo($permission->name);
            }
        }

        $this->dispatch('success-toastr', ['message' => "Administrator Level Upgraded Successfully"]);
        session()->flash('success', "Administrator Level Upgraded Successfully");
        $this->redirectRoute('admin.hr.administrator');
    }
    
    public function render()
    {
        return view('livewire.admin.hr.administrator.upgrade', [
            'roles'  => Role::get()
        ]);
    }
}
