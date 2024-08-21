<?php

namespace App\Livewire\Admin\Hr\User;

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Show extends Component
{
    public $user;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->authorize('view users');    
    }

    public function blockUser()
    {
        $user = User::find($this->user->id);
        $user->blocked_by_admin = true;
        $user->save();
        Session::flash('blocked', 'This user has been blocked');
        $this->mount($user);
    }


    public function unBlockUser()
    {
        $user = User::find($this->user->id);
        $user->blocked_by_admin = false;
        $user->save();
        Session::flash('blocked', 'This user has been unblocked.');
        $this->mount($user);
    }


    public function render()
    {
        return view('livewire.admin.hr.user.show', [
            'walletHistories' => $this->user->walletHistories()->get()->take(10)
        ]);
    }
}
