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
        $user = User::withTrashed()->find($this->user->id);
        $user->blocked_by_admin = true;
        $user->save();
        $this->dispatch('success-toastr', ['message' => "This user has been blocked"]);
        $this->mount($user->username);
    }

    public function softDelete()
    {
        $user = User::withTrashed()->find($this->user->id);
    
        if ($user->deleted_at) {
            $user->deleted_at = null;
            $message = "This user has been restored.";
        } else {
            $user->deleted_at = now();
            $message = "This user has been deleted.";
        }
    
        $user->save();
        $this->dispatch('success-toastr', ['message' => $message]);
        $this->mount($user->username);
    }


    public function unBlockUser()
    {
        $user = User::withTrashed()->find($this->user->id);
        $user->blocked_by_admin = false;
        $user->save();
        $this->dispatch('success-toastr', ['message' => "This user has been unblocked"]);
        $this->mount($user->username);
    }

    public function sendPasswordResetLink()
    {
        if (!$this->user || !$this->user->email) {
            $this->dispatch('error-toastr', ['message' => "User does not have a valid email address."]);
            return;
        }

        $status = Password::sendResetLink(['email' => $this->user->email]);
        $user = User::find($this->user->id);

        $user->pin = null;
        $user->save();

        if ($status === Password::RESET_LINK_SENT) {
            $this->dispatch('success-toastr', ['message' => "Password reset link sent to user."]);
        } else {
            $this->dispatch('error-toastr', ['message' => "Failed to send password reset link."]);
        }
    }

    public function render()
    {
        return view('livewire.admin.hr.user.show', [
            'walletHistories' => $this->user?->walletHistories()?->get()?->take(10)
        ]);
    }
}
