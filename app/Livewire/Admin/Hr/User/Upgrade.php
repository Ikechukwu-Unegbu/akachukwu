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

    public $single_transfer_limit;
    public $daily_transfer_limit;

    protected $rules = [
        'level' => ['required', 'in:ordinary,reseller'],
        'role' => ['required', 'in:admin,user'],
        'single_transfer_limit' => ['nullable', 'numeric', 'min:0'],
        'daily_transfer_limit' => ['nullable', 'numeric', 'min:0'],
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
        $this->single_transfer_limit = $this->user->single_transfer_limit;
        $this->daily_transfer_limit = $this->user->daily_transfer_limit;
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

            $this->validate([
                'assign_role' => 'required|integer'
            ]);

            $model_role = Role::find($this->assign_role);
            $this->user->assignRole($model_role->name);
            $permissions = $model_role->permissions->pluck('name', 'name')->toArray();
            $this->user->givePermissionTo($permissions);
        }

        $this->dispatch('success-toastr', ['message' => "User Level Upgraded Successfully"]);
        session()->flash('success', "User Level Upgraded Successfully");
        $this->redirectRoute('admin.hr.user');
    }

    public function updateTransferLimit()
    {
        $this->validateOnly('single_transfer_limit');
        $this->validateOnly('daily_transfer_limit');

        $this->user->update([
            'single_transfer_limit' => $this->single_transfer_limit ? $this->single_transfer_limit : NULL,
            'daily_transfer_limit' => $this->daily_transfer_limit ? $this->daily_transfer_limit : NULL,
        ]);

        $this->dispatch('success-toastr', ['message' => "Transfer limits updated successfully"]);
        session()->flash('success', "Transfer limits updated successfully");
        return redirect()->to(url()->previous());
    }

    public function render()
    {
        return view('livewire.admin.hr.user.upgrade', [
            'roles' => Role::get()
        ]);
    }
}
