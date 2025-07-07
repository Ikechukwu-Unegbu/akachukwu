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
        $this->user = User::withTrashed()
            ->with(['flaggedByAdmin', 'postNoDebitByAdmin', 'blacklistedByAdmin'])
            ->where('username', $user)->first();
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
        $message = "";

        DB::transaction(function()use($user, &$message){

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
                'flagged_by_admin_id' => null,
                'flagged_at' => null,
                'post_no_debit' => false,
                'post_no_debit_by_admin_id' => null,
                'post_no_debit_at' => null,
                'is_blacklisted' => false,
                'blacklisted_by_admin_id' => null,
                'blacklisted_at' => null,
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

    public function handlePostNoDebit()
    {
        $user = User::withTrashed()->find($this->user->id);
        DB::transaction(function()use($user){
            $user->post_no_debit = true;
            $user->post_no_debit_by_admin_id = Auth::id();
            $user->post_no_debit_at = now();
            $user->save();

            ActivityLogService::log([
                'activity'=> "Post No Debit Activation",
                'description'=> Auth::user()->name.' activated Post No Debit for '.$user->name."'s profile.",
                'type'=> 'Users',
            ]);
        });

        $this->dispatch('success-toastr', ['message' => "Post No Debit activated for user."]);
        $this->redirect(url()->previous());
    }

    public function handleFlag()
    {
        $user = User::withTrashed()->find($this->user->id);
        DB::transaction(function()use($user){
            $user->is_flagged = true;
            $user->flagged_by_admin_id = Auth::id();
            $user->flagged_at = now();
            $user->save();

            ActivityLogService::log([
                'activity'=> "Flag User",
                'description'=> Auth::user()->name.' flagged '.$user->name."'s profile.",
                'type'=> 'Users',
            ]);
        });

        $this->dispatch('success-toastr', ['message' => "User flagged successfully."]);
        $this->mount($user->username);
    }

    public function handleBlacklist()
    {
        $user = User::withTrashed()->find($this->user->id);
        DB::transaction(function()use($user){
            $user->is_blacklisted = true;
            $user->blacklisted_by_admin_id = Auth::id();
            $user->blacklisted_at = now();
            $user->save();

            ActivityLogService::log([
                'activity'=> "Blacklist User",
                'description'=> Auth::user()->name.' blacklisted '.$user->name."'s profile.",
                'type'=> 'Users',
            ]);
        });

        $this->dispatch('success-toastr', ['message' => "User blacklisted successfully."]);
        $this->mount($user->username);
    }

    public function render()
    {

        return view('livewire.admin.hr.user.show', [
            'walletHistories' => $this->user?->walletHistories()?->get()?->take(10)
        ]);
    }
}
