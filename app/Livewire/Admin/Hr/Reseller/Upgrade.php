<?php

namespace App\Livewire\Admin\Hr\Reseller;

use App\Models\User;
use Livewire\Component;

class Upgrade extends Component
{
    public $user;
    public $level;

    protected $rules = [
        'level' => ['required', 'in:ordinary,reseller']
    ];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->level = $this->user->user_level;
        $this->authorize('view users');    
    }

    public function update()
    {
        $this->validate();
        $this->user->update(['user_level' => $this->level]);
        if ($this->user->upgradeRequests()->count()) {
            $this->user->upgradeRequests()->each(function ($q) {
                $q->update(['status' => 'success']);
            });
        }
        $this->dispatch('success-toastr', ['message' => "User Level Upgraded Successfully"]);
        session()->flash('success', "User Level Upgraded Successfully");
        $this->redirectRoute('admin.hr.reseller');
    }

    public function render()
    {
        return view('livewire.admin.hr.reseller.upgrade');
    }
}
