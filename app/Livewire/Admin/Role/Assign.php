<?php

namespace App\Livewire\Admin\Role;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Assign extends Component
{
    use WithPagination;
    public Role $role;

    public $perPage = 50;
    public $perPages = [50, 100, 200];
    public $search;
    #[Rule('required|array')]
    public $assignRoles = [];
    // #[Rule('required|array')]
    public $assignPermissions = [];

    public function mount()
    {
        $this->assignPermissions = $this->role->permissions->pluck('id', 'id')->toArray();
        $this->assignRoles = $this->role->users->pluck('id', 'id')->toArray();
        $this->authorize('assign role');
    }

    public function assign()
    {
        $this->validate();

        $users = User::whereIn('id', array_keys(array_filter($this->assignRoles)))->get();
        $permissions = Permission::whereIn('id', array_keys(array_filter($this->assignPermissions)))->pluck('id', 'name')->toArray();
        

        $removeUserFromRole = Arr::where($this->assignRoles, function ($value, $key) {
            return $value === false;
        });

        if (count($removeUserFromRole) > 0) {            
            $get_users = User::whereIn('id', array_keys($removeUserFromRole))->get();
            foreach ($get_users as $user) {
                $user->removeRole($this->role->name);
                foreach ($permissions as $key => $permission) $user->revokePermissionTo($key);
            }
        }

        foreach ($users as $user) {
            if (!$user->hasRole($this->role->name)) {
                $user->assignRole($this->role->name);
                $model_has_permissions = $user->permissions->pluck('id', 'id')->toArray();
                $user->syncPermissions($permissions, $model_has_permissions);
            }
        }

        $this->dispatch('success-toastr', ['message' => Str::title($this->role->name) . " Assigned Successfully"]);
        session()->flash('success', Str::title($this->role->name) . " Assigned Successfully");
        $this->redirectRoute('admin.settings.role');
    }

    public function render()
    {
        return view('livewire.admin.role.assign', [
            'administrators'    =>  User::search($this->search)->whereRole('admin')->orderBy('name')->paginate($this->perPage)
        ]);
    }
}
