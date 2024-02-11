<?php

namespace App\Livewire\Admin\Role;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Edit extends Component
{
    public Role $role;
    public $name;
    public $assign = [];

    public function mount()
    {
        $this->name = $this->role->name;

        $this->assign = $this->role->permissions->pluck('id', 'id')->toArray();

        $this->authorize('edit role');
    }

    public function update()
    {
        $validated = $this->validate([
            'name'      =>  'required|string|max:30|unique:users,name,' . $this->role->id,
            'assign'    =>  'required|array'
        ]);
  
        $this->role->update($validated);
        $permissions = Permission::whereIn('id', array_keys(array_filter($this->assign)))->pluck('id', 'name')->toArray();
        $this->role->syncPermissions($permissions);

        $this->dispatch('success-toastr', ['message' => 'Role Updated Successfully']);
        session()->flash('success', 'Role Updated Successfully');
        $this->redirectRoute('admin.settings.role');
    }

    public function render()
    {
        return view('livewire.admin.role.edit', [
            'permissions' => Permission::get()
        ]);
    }
}
