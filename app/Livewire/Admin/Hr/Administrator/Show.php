<?php

namespace App\Livewire\Admin\Hr\Administrator;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class Show extends Component
{
    public User $user;
    public $role;
    #[Rule('required|array')]
    public $assignPermissions = [];

    public function mount()
    {
        $this->assignPermissions = DB::table('model_has_permissions')->where('model_id', $this->user->id)->pluck('permission_id', 'permission_id')->toArray();
        $this->authorize('view administrators');
    }

    public function update()
    {
        $this->validate();
        
        $permissions = Permission::whereIn('id', array_keys(array_filter($this->assignPermissions)))->pluck('id', 'name')->toArray();
        
        $this->user->syncPermissions($permissions);

        $this->dispatch('success-toastr', ['message' => "Permission Updated Successfully"]);
        session()->flash('success', "Permission Updated Successfully");
        return redirect()->to(url()->previous());
    }
    
    public function render()
    {
        return view('livewire.admin.hr.administrator.show', [
            'walletHistories' => $this->user->walletHistories()->get()->take(10)
        ]);
    }
}
