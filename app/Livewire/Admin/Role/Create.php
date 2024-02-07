<?php

namespace App\Livewire\Admin\Role;

use Livewire\Attributes\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Create extends Component
{
    #[Rule('required|string|max:30|unique:roles,name')]
    public $name;
    #[Rule('required|array')]
    public $assign = [];

    public function store()
    {
        $validated = $this->validate();

        $role = Role::create($validated);
        $permissions = Permission::whereIn('id', array_values($this->assign))->pluck('id', 'name')->toArray();
        $role->syncPermissions($permissions);

        $this->dispatch('success-toastr', ['message' => 'Role Added Successfully']);
        session()->flash('success', 'Role Added Successfully');
        $this->redirectRoute('admin.settings.role');
    }

    public function render()
    {
        return view('livewire.admin.role.create', [
            'permissions' => Permission::get()
        ]);
    }
}
