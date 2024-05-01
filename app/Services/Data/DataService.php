<?php

namespace App\Services\Data;

use App\Models\Data\DataPlan;
use App\Models\Data\DataType;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;
use Illuminate\Support\Facades\Log;
use App\Models\Data\DataTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Services\Account\AccountBalanceService;

class DataService 
{

    protected static $account;

    public static function create($vendorId, $networkId, $typeId, $dataId, $mobile_number)
    {
        try {

            self::$account = new AccountBalanceService(Auth::user());        

            $vendor = DataVendor::find($vendorId);
            $network = DataNetwork::whereVendorId($vendorId)->whereNetworkId($networkId)->first();
            $plan = DataPlan::whereVendorId($vendor->id)->whereNetworkId($network->network_id)->whereDataId($dataId)->first();
            $type = DataType::whereVendorId($vendor->id)->whereNetworkId($network->network_id)->whereId($typeId)->first();
                        
            if (! self::$account->verifyAccountBalance($plan->amount)) {
                return response()->json([
                    'status'    =>   false,
                    'error'     =>  'Insufficient Account Balance.',
                    'message'   =>  "You need at least ₦{$plan->amount} to subscribe to this plan. Please fund your wallet to continue.",
                ], 401)->getData();
            }

            $transaction = DataTransaction::create([
                'user_id'            =>  Auth::id(),
                'vendor_id'          =>  $vendor->id,
                'network_id'         =>  $network->network_id,
                'type_id'            =>  $type->id,
                'data_id'            =>  $plan->data_id,
                'amount'             =>  $plan->amount,
                'size'               =>  $plan->size,
                'validity'           =>  $plan->validity,
                'mobile_number'      =>  $mobile_number,
                'balance_before'     =>  Auth::user()->account_balance,
                'plan_network'       =>  $network->name,
                'plan_name'          =>  $plan->size,
                'plan_amount'        =>  $plan->amount,
            ]);

            $response = Http::withHeaders([
                'Authorization' => "Token " . $vendor->token,
                'Content-Type' => 'application/json'
            ])->post("{$vendor->api}/data/", [
                'network' => $network->network_id,
                'mobile_number' => $mobile_number,
                'plan' => $plan->data_id,
                'Ported_number' =>  true
            ]);

            $response = $response->object();

            if (isset($response->error)) {
                // Insufficient API Wallet Balance Error
                return response()->json([
                    'status'  => false,
                    'error'   => 'Insufficient Balance From API.',
                    'message' => "An error occurred during the Data request. Please try again later."
                ], 401)->getData();
            }

            if (isset($response->Status) && $response->Status == 'successful') {

                self::$account->transaction($plan->amount);

                $transaction->update([
                    'balance_after'     =>    self::$account->getAccountBalance(),
                    'status'            =>    true,
                    'plan_network'      =>    $response->plan_network,
                    'plan_name'         =>    $response->plan_name,
                    'plan_amount'       =>    $response->plan_amount,
                    'api_data_id'       =>    $response->ident,
                    'api_response'      =>    $response->api_response,
                ]);

                return response()->json([
                    'status'   =>    true,
                    'error'    =>    NULL,
                    'message'  =>    "Data purchase successful: {$network->name} {$plan->size} for ₦{$plan->amount} on {$mobile_number}.",
                    'response' =>    $transaction
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
                'message'   => "Opps! Unable to perform data payment. Please check your network connection."
            ])->getData();
        }
        



    }
}