<?php 
namespace App\Services\Referrals;

use App\Models\Data\DataPlan;
use App\Models\Data\DataTransaction;
use App\Models\Data\DataType;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReferralService{


    public function payReferrerForData(User $user, DataPlan $plan, DataType $type, DataTransaction $dataTrx)
    {
        DB::transaction(function()use($user, $plan, $dataTrx, $type){
            if(!$user->referralsReceived){
                return;
            }
            $bonus = $this->calculateReferrerBonus($type->referral_pay, $plan->getDiscountedAmount());
            $dataTrx->referral_pay = $bonus;
            $dataTrx->save();
            $referrer = User::find($user->referralsReceived->referrer_id);
            $referrer->bonus_balance = $referrer->bonus_balance+$bonus;
            $referrer->save();
        });
       
    }

    public function calculateReferrerBonus($percentage, $amountUserPaid)
    {
        if (!is_numeric($percentage) || !is_numeric($amountUserPaid) || $percentage < 0 || $amountUserPaid < 0) {
            throw new \InvalidArgumentException('Invalid input values. Percentage and amount must be positive numbers.');
        }
    
        $bonus = ($percentage / 100) * $amountUserPaid;
    
       
        return $bonus;
    }

    public function reverseRferrerpay(DataTransaction $dataTransaction)
    {
       return DB::transaction(function()use($dataTransaction){
            if(!$dataTransaction->user->referralsReceived){
                return;
            }
            User::lockForUpdate()->find($dataTransaction->user->id);
            $referrer = User::lockForUpdate()->find($dataTransaction->user->referralsReceived->referrer_id);
            $referrer->bonus_balance = $referrer->bonus_balance-$dataTransaction->referral_pay;
            $referrer->save();
       });
    }
    
}