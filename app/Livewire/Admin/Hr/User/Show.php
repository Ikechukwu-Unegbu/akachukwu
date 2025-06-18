<?php

namespace App\Livewire\Admin\Hr\User;

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use App\Services\Admin\Activity\ActivityLogService;

class Show extends Component
{
    public $user;

    public function mount( $user)
    {

        ActivityLogService::log([
            'activity'=>"View",
            'description'=>Auth::user()->name.' viewed '.$user."'s profile.",
            'type'=>'Users',
        ]);
        $this->user = User::withTrashed()->where('username', $user)->first();
        $this->authorize('view users'); 

        //$this->user = User::withTrashed()->where('username', $user)->firstOrFail();
        //$this->authorize('view users');

    }

    public function blockUser()
    {
        $user = User::withTrashed()->find($this->user->id);
        DB::transaction(function()use($user){
           
            $user->blocked_by_admin = true;
            $user->save();
            $this->dispatch('success-toastr', ['message' => "This user has been blocked"]);
            ActivityLogService::log([
                'activity'=>"Blocking",
                'description'=>Auth::user()->name.' Blocked '.$user->name."'s profile.",
                'type'=>'Users',
            ]);
        });
    
        $this->mount($user->username);
    }

    public function softDelete()
    {

        $user = User::withTrashed()->find($this->user->id);

    
        DB::transaction(function()use($user){
         
            if ($user->deleted_at) {
                $user->deleted_at = null;
                $message = "This user has been restored.";
            } else {
                $user->deleted_at = now();
                $message = "This user has been deleted.";
            }
        
            $user->save();
            ActivityLogService::log([
                'activity'=>"Blocking",
                'description'=>Auth::user()->name.' Soft deleted '.$user->name."'s profile.",
                'type'=>'Users',
            ]);
        });
      

        $this->dispatch('success-toastr', ['message' => $message]);
        $this->mount($user->username);
    }


    public function dropAllFlags()
    {
        // dd('good');
           $this->user->update([
                'is_flagged' => false,
                'post_no_debit' => false,
                'is_blacklisted' => false,
            ]);
        $this->dispatch('success-toastr','All flags dropped.');
        $this->mount($this->user->username);
    }


    public function unBlockUser()
    {

        $user = User::withTrashed()->find($this->user->id);
        DB::transaction(function()use($user){
            $user->blocked_by_admin = false;
            $user->save();

            ActivityLogService::log([
                'activity'=>"Blocking",
                'description'=>Auth::user()->name.' Unblocked '.$user->name."'s profile.",
                'type'=>'Users',
            ]);
        });

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
        DB::transaction(function()use($user){
            $user->pin = null;
            $user->save();
            ActivityLogService::log([
                'activity'=>"Blocking",
                'description'=>Auth::user()->name.' sent password reset to '.$user->name."'s profile.",
                'type'=>'Users',
            ]);
        });


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
