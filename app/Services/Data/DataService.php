<?php

namespace App\Services\Data;

use App\Models\Data\DataTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class DataService 
{
    public function data(object $vendor, object $network, object $type, object $plan, $mobile_number, object $user)
    {
        
        if (! $this->verifyAccountBalance($user, $plan)) {
            return json_encode([
                'error' => 'Insufficient Account Balance.',
                'message' => "You need at least {$plan->amount} to subscribe to this plan. Please fund your account to continue.",
            ]);
        }

        $transaction = DataTransaction::create([
            'user_id'            =>  $user->id,
            'vendor_id'          =>  $vendor->id,
            'network_id'         =>  $network->network_id,
            'type_id'            =>  $type->id,
            'data_id'            =>  $plan->data_id,
            'amount'             =>  $plan->amount,
            'size'               =>  $plan->size,
            'validity'           =>  $plan->validity,
            'mobile_number'      =>  $mobile_number,
            'balance_before'     =>  $user->account_balance,
            'plan_network'       =>  $network->name,
            'plan_name'          =>  $plan->size,
            'plan_amount'        =>  $plan->amount,
        ]);

        try {

            $response = Http::withHeaders([
                'Authorization' => "Token " . $vendor->token,
                'Content-Type' => 'application/json',
            ])->post("{$vendor->api}/data/", [
                'network' => $network->network_id,
                'mobile_number' => $mobile_number,
                'plan' => $plan->data_id,
                'Ported_number' =>  true
            ]);

            // if (! $response->ok()) {
            //     throw new \Exception('Invalid Response From Payment Gateway');
            // }

            return json_encode([
                'transaction'   => $transaction,
                'response'      =>  $response->object()
            ]);
            
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return json_encode([
                'error' => 'Error.',
                'message' => "Unable to Perform transaction. Please try again later.",
            ]);
        }
    }

    private function verifyAccountBalance($user, $plan) : bool
    {
        $account_balance = $user->account_balance;

        if ($account_balance >= $plan->amount) return true;

        return false;
    }

}