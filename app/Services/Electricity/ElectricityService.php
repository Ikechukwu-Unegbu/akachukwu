<?php

namespace App\Services\Electricity;

use App\Models\Data\DataTransaction;
use App\Models\Utility\ElectricityTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ElectricityService 
{

    public function __construct(
        private object $vendor, 
        private object $electricity,
        private int $meterNumber,
        private int $meterType,
        private object $user
    ) {}

    public function BillPayment($amount, $customerMobile)
    {
        if (! $this->verifyAccountBalance($this->user, $amount)) {
            return json_encode([
                'error' => 'Insufficient Account Balance.',
                'message' => "You need at least ₦{$amount} to subscribe to this plan. Please fund your account to continue.",
            ]);
        }

        if (! $this->validateMeterNumber($this->meterNumber,$this->electricity->disco_name, $this->meterType)) {
            return json_encode([
                'error' => 'Oops!',
                'message' => "The Meter Number provided is Invalid ({$this->meterNumber}).",
            ]);
        }

        $transaction = ElectricityTransaction::create([
            'user_id'                   =>  $this->user->id,
            'vendor_id'                 =>  $this->vendor->id,
            'disco_id'                  =>  $this->electricity->disco_id,
            'disco_name'                =>  $this->electricity->disco_name,
            'meter_number'              =>  $this->meterNumber,
            'meter_type_id'             =>  $this->meterType,
            'meter_type_name'           =>  $this->meterType == 1 ? 'Prepaid' : 'Postpaid',
            'amount'                    =>  $amount,
            'customer_mobile_number'    =>  $customerMobile,
            'balance_before'            =>  $this->user->account_balance
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => "Token " . $this->vendor->token,
                'Content-Type' => 'application/json',
            ])->post("{$this->vendor->api}/billpayment/", [
                'disco_name'   => $transaction->disco_id,
                'meter_number' => $transaction->meter_number,
                'amount'       => $amount,
                'MeterType'    => $transaction->meter_type_name
            ]);
    
            return json_encode([
                'transaction'   =>  $transaction,
                'response'      =>  $response->object()
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return json_encode([
                'error' => 'Opps.',
                'message' => "Unable to Perform Bill transaction. Please check your network connection.",
            ]);
        }

    }

    protected function verifyAccountBalance($user, $amount) : bool
    {
        $account_balance = $user->account_balance;

        if ($account_balance >= $amount) return true;

        return false;
    }
    

    protected function validateMeterNumber($meter, $discoId, $meterType) : bool
    {

        $meterType =  $meterType == 1 ? 'PREPAID' : 'POSTPAID';

        $response = Http::withHeaders([
            'Authorization' => "Token " . $this->vendor->token,
            'Content-Type' => 'application/json',
        ])->get("{$this->vendor->api}/validatemeter?meternumber={$meter}&disconame={$discoId}&mtype={$meterType}");
        
        $response = $response->object();
                
        if (!$response->invalid) return true;

        return false;
    }
}