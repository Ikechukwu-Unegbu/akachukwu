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

            $vendor = DataVendor::find($vendorId);
            $network = DataNetwork::whereVendorId($vendorId)->whereNetworkId($networkId)->first();

            static::$accountBalance = new AccountBalanceService(Auth::user());

            if (! static::$accountBalance->verifyAccountBalance($amount)) {
                return json_encode([
                    'error' => 'Insufficient Account Balance.',
                    'message' => "You need at least ₦{$amount} to purchase this plan. Please fund your account to continue.",
                ]);
            }

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

            if (!$response->ok()) {
                return json_encode([
                    'status'  => false,
                    'error'   => 'API response error!',
                    'message' => "An error occurred during the Airtime request. Please try again later",
                ]);
            }

            $response = $response->object();

            if (isset($response->error)) {
                // Insufficient API Wallet Balance Error
                return json_encode([
                    'status'  => false,
                    'error'   => 'Insufficient Balance From API.',
                    'message' => "An error occurred during the Airtime request. Please try again later",
                ]);
            }

            if (isset($response->Status) && $response->Status == 'successful') {

                static::$accountBalance->transaction($amount);

                $transaction->update([
                    'balance_after'     =>    static::$accountBalance->getAccountBalance(),
                    'status'            =>    true,
                    'api_data_id'       =>    $response->ident
                    // 'api_response'      =>    $response->response->api_response,
                ]);

                return json_encode([
                    'status'  => true,
                    'error'   => NULL,
                    'message' => "Airtime Purchased Successfully. You purchased {$network->name} ₦{$amount} for {$mobile_number}",
                ]);
            }

            return json_encode([
                'status'    => false,
                'error'     => 'Server Error',
                'message'   => "Opps! Unable to Perform transaction. Please try again later.",
            ]);
            
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return json_encode([
                'status'    => true,
                'error'     => $th->getMessage(),
                'message'   => "Opps! Unable to Perform transaction. Please try again later.",
            ]);
        }

    }

    public function validateUserData($data)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            // Add more validation rules as needed
        ];

        // return Validator::make($data, $rules)->validate();
    }

    // private function verifyAccountBalance($user, $amount) : bool
    // {
    //     $account_balance = $user->account_balance;

    //     if ($account_balance >= $amount) return true;

    //     return false;
    // }

}