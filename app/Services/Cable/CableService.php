<?php

namespace App\Services\Cable;

use App\Models\Data\DataTransaction;
use App\Models\Utility\CableTransaction;
use App\Models\Utility\ElectricityTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CableService 
{

    public function __construct(
        private object $vendor, 
        private object $cable,
        private object $cablePlan,
        private $iucNumber,
        private $customer,
        private object $user
    ) {}

    public function CableSub()
    {
        if (! $this->verifyAccountBalance($this->user, $this->cablePlan->amount)) {
            return json_encode([
                'error' => 'Insufficient Account Balance.',
                'message' => "You need at least ₦{$this->cablePlan->amount} to subscribe to this plan. Please fund your account to continue.",
            ]);
        }
        

        // if (! $this->validateIUCNumber($this->iucNumber, $this->cable->cable_name)) {
        //     return json_encode([
        //         'error' => 'Invalid IUC/SMARTCARD.',
        //         'message' => "Please provide a valid IUC/SMARTCARD",
        //     ]);
        // }

        $transaction = CableTransaction::create([
            'user_id'             =>  $this->user->id,
            'vendor_id'           =>  $this->vendor->id,
            'cable_name'          =>  $this->cable->cable_name,
            'cable_id'            =>  $this->cable->cable_id,
            'cable_plan_name'     =>  $this->cablePlan->package,
            'cable_plan_id'       =>  $this->cablePlan->cable_plan_id,
            'smart_card_number'   =>  $this->iucNumber,
            'customer_name'       =>  $this->customer,
            'amount'              =>  $this->cablePlan->amount,
            'balance_before'      =>  $this->user->account_balance,
        ]);

        try {

            $response = Http::withHeaders([
                'Authorization' => "Token " . $this->vendor->token,
                'Content-Type' => 'application/json',
            ])->post("{$this->vendor->api}/cablesub/", [
                'cablename'         =>  $this->cable->cable_id,
                'cableplan'         =>  $this->cablePlan->cable_plan_id,
                'smart_card_number' =>  $this->iucNumber
            ]);

            return json_encode([
                'transaction'   => $transaction,
                'response'      =>  $response->object()
            ]);
            
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return json_encode([
                'error' => 'Error.',
                'message' => "Unable to Perform Cable transaction. Please try again later.",
            ]);
        }

    }

    protected function verifyAccountBalance($user, $amount) : bool
    {
        $account_balance = $user->account_balance;

        if ($account_balance >= $amount) return true;

        return false;
    }
    

    protected function validateIUCNumber($iucNumber, $cable) : bool
    {

        $response = Http::withHeaders([
            'Authorization' => "Token " . $this->vendor->token,
            'Content-Type' => 'application/json',
        ])->get("{$this->vendor->api}/validateiuc/?smart_card_number={$iucNumber}&cablename={$cable}");
   
        $response = $response->object();
        
        if (!$response->invalid) return true;

        return false;
    }


}