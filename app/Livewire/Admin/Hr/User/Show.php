<?php

namespace App\Livewire\Admin\Hr\User;

use App\Models\User;
use Livewire\Component;

class Show extends Component
{
    public $user;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->authorize('view users');    
    }

    public function render()
    {
        return view('livewire.admin.hr.user.show', [
            'walletHistories' => $this->user->walletHistories()->get()->take(10)
        ]);
    }
}
