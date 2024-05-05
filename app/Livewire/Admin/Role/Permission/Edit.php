<?php

namespace App\Livewire\Admin\Role\Permission;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class Edit extends Component
{
    public $role;
    public User $user;
    #[Rule('required|array')]
    public $assignPermissions = [];

    public function mount(Request $request)
    {
        $this->authorize('assign role');
        $this->role = Role::findById($request->role);

        if (!$this->user->hasRole($this->role->id)) {
            $this->dispatch('error-toastr', ['message' => "The Admin User has not been assigned to this Role."]);
            session()->flash('error', "The Admin User has not been assigned to this Role.");
            return $this->redirectRoute('admin.settings.role.assign', $this->role->id);
        }

        $this->assignPermissions = DB::table('model_has_permissions')->where('model_id', $this->user->id)->pluck('permission_id', 'permission_id')->toArray();
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
