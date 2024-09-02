<?php 
namespace App\Services\Referrals;

use App\Helpers\GeneralHelpers;
use App\Models\Payment\VastelTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApiReferralService{

    public function getRefs()
    {
        $user = User::find(Auth::user()->id);
        $refs = $user->getReferredUsersWithEarnings();
        return $refs;
    }

    public function withdraw_referral_earning()
    {
        $vastelTransaction = new VastelTransaction();
        $vastelTransaction->admin_id = Auth::user()->id;
        $vastelTransaction->user_id = auth()->user()->id;
        $vastelTransaction->amount = auth()->user()->bonus_balance;
        $vastelTransaction->reference_id = GeneralHelpers::generateUniqueRef('vastel_transactions');
        $vastelTransaction->type = false;
        $vastelTransaction->currency = 'NGN';
        $vastelTransaction->status = true;
        $vastelTransaction->description = 'referral withdrawal';
        $vastelTransaction->save();
       

        $user = User::find(Auth::user()->id);
        $user->setAccountBalance($user->bonus_balance);
        $user->bonus_balance = 0;
        $user->save();
    }
}