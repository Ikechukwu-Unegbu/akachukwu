<?php

namespace App\Livewire\Admin\Hr\User;

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Illuminate\Support\Facades\Password;

class Show extends Component
{
    public $user;

    public function mount( $user)
    {
        $this->user = User::withTrashed()->where('username', $user)->first();
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

    public function softDelete()
    {
        $user = User::find($this->user->id);
        $user->deleted_at = now();
        $user->save();
        Session::flash('blocked', 'This user has been deleted');
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

    public function sendPasswordResetLink()
    {
        if (!$this->user || !$this->user->email) {
            Session::flash('error', 'User does not have a valid email address.');
            return;
        }

        $status = Password::sendResetLink(['email' => $this->user->email]);
        $user = User::find($this->user->id);

        $user->pin = null;
        $user->save();

        if ($status === Password::RESET_LINK_SENT) {
            Session::flash('success', 'Password reset link sent to user.');
        } else {
            Session::flash('error', 'Failed to send password reset link.');
        }
    }

    public function render()
    {
        return view('livewire.admin.hr.user.show', [
            'walletHistories' => $this->user->walletHistories()->get()->take(10)
        ]);
    }
}
