<?php

namespace App\Services\Airtime;

use App\Models\Utility\AirtimeTransaction;
use App\Services\Account\AccountBalanceService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class AirtimeService 
{
    private $accountBalance;

    public function __construct(
        private object $vendor, 
        private object $network, 
        private object $user
    ) {
        $this->accountBalance = new AccountBalanceService($user);
    }

    public function airtime($amount, $mobile_number)
    {
        
        if (! $this->accountBalance->verifyAccountBalance($amount)) {
            return json_encode([
                'error' => 'Insufficient Account Balance.',
                'message' => "You need at least ₦{$amount} to purchase this plan. Please fund your account to continue.",
            ]);
        }

        $transaction = AirtimeTransaction::create([
            'user_id'           =>  $this->user->id,
            'vendor_id'         =>  $this->vendor->id,
            'network_id'        =>  $this->network->network_id,
            'amount'            =>  $amount,
            'mobile_number'     =>  $mobile_number,
            'balance_before'    =>  $this->user->account_balance,
        ]);

        try {

            $response = Http::withHeaders([
                'Authorization' => "Token " . $this->vendor->token,
                'Content-Type' => 'application/json',
            ])->post("{$this->vendor->api}/topup/", [
                'network'       => $this->network->network_id,
                'amount'        => $amount,
                'mobile_number' => $mobile_number,
                'airtime_type'  => "VTU",
                'Ported_number' =>  true
            ]);
           
            return json_encode([
                'transaction'   =>  $transaction,
                'response'      =>  $response->object()
            ]);
            
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return json_encode([
                'error' => 'Opps!',
                'message' => "Unable to Perform transaction. Please try again later.",
            ]);
        }

    }

    private function verifyAccountBalance($user, $amount) : bool
    {
        $account_balance = $user->account_balance;

        if ($account_balance >= $amount) return true;

        return false;
    }

}