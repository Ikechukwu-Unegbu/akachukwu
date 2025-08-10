<?php 
namespace App\Services\Referral;
use App\Models\User;
use App\Models\Data\DataTransaction;
use App\Models\Utility\AirtimeTransaction;
use App\Models\Utility\ElectricityTransaction;
use App\Models\Utility\CableTransaction;
use App\Services\Payment\Transfer\VastelMoneyTransfer;
use  App\Models\MoneyTransfer;
use  App\Models\PalmPayTransaction;
use App\Models\Payment\MonnifyTransaction;


class ReferralContestConditionService{

    public function hasBillableTransaction($username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return false; // No such user
        }

        // Get user_id for transactions
        $userId = $user->id;

        // Sum amounts from all relevant transaction tables
        $totalAmount =
            DataTransaction::where('user_id', $userId)->sum('amount') +
            AirtimeTransaction::where('user_id', $userId)->sum('amount') +
            ElectricityTransaction::where('user_id', $userId)->sum('amount') +
            CableTransaction::where('user_id', $userId)->sum('amount');

        // Check if total exceeds 1000
        return $totalAmount > 1000;
    }


     /**
     * Check if the user has funded their account up to a given amount.
     *
     * @param string $username
     * @param float $threshold
     * @return bool
     */
    public function hasFundedAccount($username, $threshold = 5000)
    {
        // Get the user
        $user = User::where('username', $username)->first();

        if (!$user) {
            return false; // User not found
        }

        // Sum PalmPay transaction amounts for the user
        $palmpayTotal = PalmPayTransaction::where('user_id', $user->id)->sum('amount');

        // Sum Monnify transaction amounts for the user
        $monnifyTotal = MonnifyTransaction::where('user_id', $user->id)->sum('amount');

        // Calculate grand total
        $totalFunded = $palmpayTotal + $monnifyTotal;

        // Return true if total meets or exceeds threshold
        return $totalFunded >= $threshold;
    }

    
    
    public function hasNinOrBvn($username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return false; // User not found
        }

        return !is_null($user->nin) || !is_null($user->bvn);
    }

}