<?php

namespace App\Livewire\Admin\Role\Permission;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Edit extends Component
{
    public Role $role;
    public User $user;
    #[Rule('required|array')]
    public $assignPermissions = [];

    public function mount()
    {
        $this->assignPermissions = DB::table('model_has_permissions')->where('model_id', $this->user->id)->pluck('permission_id', 'permission_id')->toArray();
        $this->authorize('assign role');
    }

    public function update()
    {
        $this->validate();
        
        $permissions = Permission::whereIn('id', array_keys(array_filter($this->assignPermissions)))->pluck('id', 'name')->toArray();
        
        $this->user->syncPermissions($permissions);

        $this->dispatch('success-toastr', ['message' => "Permission Updated Successfully"]);
        session()->flash('success', "Permission Updated Successfully");
        $this->redirectRoute('admin.settings.role.assign', $this->role->id);
    }
    
    public function render()
    {
        return view('livewire.admin.role.permission.edit');
    }
}
