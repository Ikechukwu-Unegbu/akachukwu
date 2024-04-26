<?php

namespace App\Services\Airtime;

use App\Models\Utility\AirtimeTransaction;
use App\Services\Account\AccountBalanceService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;

class AirtimeService 
{
    private static $accountBalance;

    public static function create($vendorId, $networkId, $amount, $mobile_number)
    {
        try {

            if ($amount < 50) {
                return response()->json([
                    'status'  => false,
                    'error' => 'Insufficient Account Balance.',
                    'message' => "The minimum airtime topup is ₦50"
                ], 401)->getData();
            }

            self::$accountBalance = new AccountBalanceService(Auth::user());

            if (! self::$accountBalance->verifyAccountBalance($amount)) {
                return response()->json([
                    'status'  => false,
                    'error' => 'Insufficient Account Balance.',
                    'message' => "You need at least ₦{$amount} to purchase this plan. Please fund your wallet to continue."
                ], 401)->getData();
            }

            $vendor = DataVendor::find($vendorId);
            $network = DataNetwork::whereVendorId($vendorId)->whereNetworkId($networkId)->first();

            // Initiate Airtime Transaction
            $transaction = AirtimeTransaction::create([
                'user_id'           =>  Auth::id(),
                'vendor_id'         =>  $vendor->id,
                'network_id'        =>  $network->network_id,
                'network_name'      =>  $network->name,
                'amount'            =>  $amount,
                'mobile_number'     =>  $mobile_number,
                'balance_before'    =>  Auth::user()->account_balance,
            ]);
            
            $response = Http::withHeaders([
                'Authorization' => "Token " . $vendor->token,
                'Content-Type' => 'application/json',
            ])->post("{$vendor->api}/topup/", [
                'network'       => $network->network_id,
                'amount'        => $amount,
                'mobile_number' => $mobile_number,
                'airtime_type'  => "VTU",
                'Ported_number' =>  true
            ]);

            $response = $response->object();

            if (isset($response->error)) {
                // Insufficient API Wallet Balance Error
                return response()->json([
                    'status'  => false,
                    'error'   => 'Insufficient Balance From API.',
                    'message' => "An error occurred during the Airtime request. Please try again later."
                ], 401)->getData();
            }

            if (isset($response->Status) && $response->Status == 'successful') {

                self::$accountBalance->transaction($amount);

                $transaction->update([
                    'balance_after'     =>    self::$accountBalance->getAccountBalance(),
                    'status'            =>    true,
                    'api_data_id'       =>    $response->ident
                ]);

                return response()->json([
                    'status'  =>    true,
                    'error'   =>    NULL,
                    'message' =>    "Airtime purchased successfully. You have successfully purchased ₦{$amount} {$network->name} airtime for the phone number {$mobile_number}."
                ], 200)->getData();
            }

            return response()->json([
                'status'    => false,
                'error'     => 'Server Error',
                'message'   => "Opps! Unable to Perform transaction. Please try again later."
            ], 401)->getData();
            
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'status'    => false,
                'error'     => $th->getMessage(),
                'message'   => "Opps! Unable to perform airtime payment. Please check your network connection."
            ], 401)->getData();
        }

    }
}