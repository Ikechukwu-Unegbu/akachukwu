<?php

namespace App\Services\Vendor;

use App\Models\User;
use App\Models\Vendor;
use App\Helpers\ApiHelper;
use Illuminate\Support\Str;
use App\Models\Data\DataPlan;
use App\Models\Data\DataType;
use App\Models\Utility\Cable;
use App\Models\Data\DataNetwork;
use App\Models\Utility\CablePlan;
use Illuminate\Support\Facades\DB;
use App\Helpers\Admin\VendorHelper;
use App\Models\Utility\Electricity;
use App\Services\CalculateDiscount;
use Illuminate\Support\Facades\Log;
use App\Models\Data\DataTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Education\ResultChecker;
use App\Models\Utility\CableTransaction;
use App\Models\Utility\AirtimeTransaction;
use App\Services\Referrals\ReferralService;
use App\Actions\Idempotency\IdempotencyCheck;
use App\Models\Utility\ElectricityTransaction;
use App\Services\Account\AccountBalanceService;
use App\Services\Beneficiary\BeneficiaryService;
use App\Models\Education\ResultCheckerTransaction;

class PosTraNetService
{
    protected static $vendor;
    protected static $authUser;

    protected const WALLET_URL = "/user/";
    protected const AIRTIME_URL = "/topup/";
    protected const RESULT_CHECKER_URL = "/epin/";
    protected const ELECTRICITY_URL = "/billpayment/";
    protected const CABLE_URL = "/cablesub/";
    protected const DATA_URL = "/data/";

    public function __construct(Vendor $vendor)
    {
        self::$vendor = $vendor;
        self::$authUser = new AccountBalanceService(Auth::user());
    }

    protected static function headers()
    {
        return [
            'Authorization' => "Token " . self::$vendor->token,
            'Content-Type'  => 'application/json'
        ];
    }

    protected static function url($url, $data = [])
    {
        $url = self::$vendor->api . $url;

        $response = Http::withHeaders(static::headers())->post($url, $data);

        return (is_object($response->object())) ? $response->object() : null;
    }

    public static function getWalletBalance()
    {
        try {
            $url = self::$vendor->api . self::WALLET_URL;

            $token = self::$vendor->token;

            $response = Http::withHeaders(static::headers($token))->get($url);

            $response = ($response->object()) ? $response->object() : null;

            if ($response)
                return response()->json([
                    'status'    =>  true,
                    'response'  => number_format($response->user->Account_Balance, 2)
                ], 200)->getData();


            return response()->json([
                'status'    =>  false,
                'response'  => []
            ], 401)->getData();
        } catch (\Throwable $th) {
        }
    }

 

    public static function airtime($networkId, $amount, $mobileNumber)
    {
        try {
            // Start a database transaction to ensure atomicity
            return DB::transaction(function () use ($networkId, $amount, $mobileNumber) {
               
                $network = DataNetwork::whereVendorId(self::$vendor->id)->whereNetworkId($networkId)->first();
                $discount = $network->airtime_discount;


                sleep(random_int(1, 5));
                if(Auth::check()){
                    if(Auth::user()->account_balance < $amount){
                        return ApiHelper::sendError('Insufficient balance', "You dont have enough money for this transaction.");
                    }
                }

              
                $duplicateTransaction = IdempotencyCheck::checkDuplicateTransaction(
                    AirtimeTransaction::class, 
                    [
                        'user_id' => Auth::id(), 
                        'vendor_id' => (int) self::$vendor->id, 
                        'network_id' => (int) $network->network_id, 
                        'mobile_number' => $mobileNumber,
                        'amount' => number_format($amount, 2, '.', '')
                    ],
                );
                
                // Handle duplicate transactions
                if ($duplicateTransaction) {
                    return ApiHelper::sendError($duplicateTransaction, "Transaction is already pending or recently completed. Please wait.");
                }

                // Lock the user's account balance to prevent double spending
                $user = User::where('id', Auth::id())->lockForUpdate()->firstOrFail();

                // Create an airtime transaction record
                $transaction = AirtimeTransaction::create([
                    'user_id' => Auth::id(),
                    'vendor_id' => self::$vendor->id,
                    'network_id' => $network->network_id,
                    'network_name' => $network->name,
                    'amount' => $amount,
                    'mobile_number' => $mobileNumber,
                    'balance_before' => $user->account_balance,
                    'balance_after' => $user->account_balance,
                    'discount' => $discount
                ]);
                
                 // If the user is a reseller, apply any discounts
                 $discountedAmount = $amount;
                 if (auth()->user()->isReseller()) {
                    $discountedAmount = CalculateDiscount::applyDiscount($discountedAmount, 'airtime');
                }
                // Apply any other discounts (if applicable)
                $discountedAmount = CalculateDiscount::calculate($discountedAmount, $discount);

                // Deduct the amount from the user's balance if they have enough funds
                if ($user->account_balance >= $amount) {
                    $user->account_balance -= $discountedAmount;  // Deduct the amount
                    $user->save();  // Save the updated balance
                } else {
                    // Insufficient funds
                    $errorResponse = [
                        'error' => 'Insufficient Balance.',
                        'message' => 'Your account balance is insufficient to complete this transaction.'
                    ];
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }

                // Send the request to the API for airtime purchase
                $data = [
                    'network' => $network->network_id,
                    'amount' => $amount,
                    'mobile_number' => $mobileNumber,
                    'airtime_type' => "VTU",
                    'Ported_number' => true
                ];

                // Make the external API call
                $response = static::url(self::AIRTIME_URL, $data);

                // Store the API response in the transaction
                self::storeApiResponse($transaction, $response);

               
                self::$authUser->transaction($amount);

                // Handle the response from the API
                if (isset($response->error)) {
                    // Handle API wallet balance issues
                    self::$authUser->initiateRefund($amount, $transaction);
                    $errorResponse = [
                        'error' => 'Insufficient Balance From API.',
                        'message' => "An error occurred during the Airtime request. Please try again later."
                    ];
                    Log::error($response->error);
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }

                // If the response indicates success
                if (isset($response->Status) && $response->Status == 'successful') {
                    // Update the transaction after successful API response
                    $transaction->update([
                        // 'balance_after' => self::$authUser->getAccountBalance(),
                        'api_data_id' => $response->id ?? $response->ident
                    ]);

                    self::$authUser->initiateSuccess($discountedAmount, $transaction);

                    // Record the beneficiary for this transaction
                    BeneficiaryService::create($transaction->mobile_number, 'airtime', $transaction);

                    return ApiHelper::sendResponse($transaction, "Airtime purchase successful: ₦{$amount} {$network->name} airtime added to {$mobileNumber}.");
                }

                // If the API response indicates failure
                if (isset($response->Status) && $response->Status == 'failed') {
                    $errorResponse = [
                        'error' => 'API response Error',
                        'message' => "Airtime purchase failed. Please try again later.",
                    ];
                    self::$authUser->initiatePending($discountedAmount, $transaction);
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }

                // Handle unexpected errors
                $errorResponse = [
                    'error' => 'Server Error',
                    'message' => "Opps! Unable to Perform transaction. Please try again later.",
                ];
                self::$authUser->initiatePending($discountedAmount, $transaction);
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            });  // End of DB transaction
        } catch (\Throwable $th) {
            // Log any errors and send the response
            Log::error($th->getMessage());
            $errorResponse = [
                'error' => $th->getMessage(),
                'message' => "Opps! Unable to perform airtime payment. Please check your network connection."
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }


    public static function electricity($discoId, $meterNumber, $meterType, $amount, $customerName, $customerMobile, $customerAddress)
    {
        try {
            return DB::transaction(function () use ($discoId, $meterNumber, $meterType, $amount, $customerName, $customerMobile, $customerAddress) {
    
                if ($amount < 500) {
                    $errorResponse = [
                        'error' => 'Minimum account error',
                        'message' => "The minimum amount is ₦500"
                    ];
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }
    
                $user = User::where('id', Auth::id())->lockForUpdate()->firstOrFail();
    
                $verifyAccountBalance = self::verifyAccountBalance($amount);
                if (!$verifyAccountBalance->status) {
                    return ApiHelper::sendError($verifyAccountBalance->error, $verifyAccountBalance->message);
                }
    
                $vendor = self::$vendor;
                $electricity = Electricity::whereVendorId($vendor->id)->whereDiscoId($discoId)->first();
                $discount = $electricity->discount;
    
                $transaction = ElectricityTransaction::create([
                    'user_id' => Auth::id(),
                    'vendor_id' => $vendor->id,
                    'disco_id' => $electricity->disco_id,
                    'disco_name' => $electricity->disco_name,
                    'meter_number' => $meterNumber,
                    'meter_type_id' => $meterType,
                    'meter_type_name' => $meterType == 1 ? 'Prepaid' : 'Postpaid',
                    'amount' => $amount,
                    'customer_mobile_number' => $customerMobile,
                    'customer_name' => $customerName,
                    'customer_address' => $customerAddress,
                    // 'balance_before' => $user->account_balance,
                    // 'balance_after' => $user->account_balance,
                    'discount' => $discount
                ]);

                $discountedAmount = $amount;
                if (auth()->user()->isReseller()) {
                    $discountedAmount = CalculateDiscount::applyDiscount($discountedAmount, 'electricity');
                }

                $discountedAmount = CalculateDiscount::calculate($discountedAmount, $discount);
    
                // Deduct the amount from the user's account balance
                if ($user->account_balance >= $amount) {
                    $user->account_balance -= $discountedAmount;
                    $user->save();
                } else {
                    $errorResponse = [
                        'error' => 'Insufficient balance',
                        'message' => 'Your account balance is insufficient to complete this purchase.'
                    ];
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }
    
                $data = [
                    'disco_name' => $transaction->disco_id,
                    'meter_number' => $transaction->meter_number,
                    'amount' => $amount,
                    'MeterType' => $transaction->meter_type_name,
                    'Customer_Phone' => $transaction->customer_mobile_number,
                    'customer_name' => $transaction->customer_name,
                    'customer_address' => $transaction->customer_address
                ];
    
                $response = self::url(self::ELECTRICITY_URL, $data);
                self::storeApiResponse($transaction, $response);
    
                if (isset($response->error)) {
                    self::$authUser->initiateRefund($discountedAmount, $transaction);
                    $errorResponse = [
                        'error' => 'Insufficient Balance From API.',
                        'message' => "An error occurred during bill payment request. Please try again later."
                    ];
                    Log::error($response->error);
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }
    
                if (isset($response->Status) && $response->Status == 'successful') {
                    $transaction->update([
                        // 'balance_after' => self::$authUser->getAccountBalance(),
                        'token' => VendorHelper::removeTokenPrefix($response->token),
                        'api_data_id' => $response->id ?? $response->ident
                    ]);
                    self::$authUser->initiateSuccess($discountedAmount, $transaction);
                    BeneficiaryService::create($transaction->meter_number, 'electricity', $transaction);
    
                    return ApiHelper::sendResponse($transaction, "Bill payment successful: ₦{$transaction->amount} {$transaction->meter_type_name} for ({$transaction->meter_number}).");
                }
    
                if (isset($response->Status) && $response->Status == 'failed') {
                    $errorResponse = [
                        'error' => 'API response Error',
                        'message' => "Bill purchase failed. Please try again later.",
                    ];
                    self::$authUser->initiatePending($discountedAmount, $transaction);
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }
    
                $errorResponse = [
                    'error' => 'Server Error',
                    'message' => "Oops! Unable to perform transaction. Please try again later."
                ];
    
                self::$authUser->initiatePending($discountedAmount, $transaction);
    
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            });
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $errorResponse = [
                'error' => 'Network connection error',
                'message' => 'Oops! Unable to make payment. Please check your network connection.'
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }
    

    public static function cable($cableId, $cablePlan, $iucNumber, $customer)
    {
        try {
            return DB::transaction(function () use ($cableId, $cablePlan, $iucNumber, $customer) {
                $vendor = self::$vendor;
                $cable = Cable::whereVendorId($vendor->id)->whereCableId($cableId)->first();
                $cable_plan = CablePlan::whereVendorId($vendor->id)->whereCablePlanId($cablePlan)->first();

                // Lock the user record to prevent double spending
                $user = User::where('id', Auth::id())->lockForUpdate()->firstOrFail();

                $verifyAccountBalance = self::verifyAccountBalance($cable_plan->amount);
                if (! $verifyAccountBalance->status) {
                    return ApiHelper::sendError($verifyAccountBalance->error, $verifyAccountBalance->message);
                }

                $discount = $cable->discount;

                $transaction = CableTransaction::create([
                    'user_id'             =>  $user->id,
                    'vendor_id'           =>  $vendor->id,
                    'cable_name'          =>  $cable->cable_name,
                    'cable_id'            =>  $cable->cable_id,
                    'cable_plan_name'     =>  $cable_plan->package,
                    'cable_plan_id'       =>  $cable_plan->cable_plan_id,
                    'smart_card_number'   =>  $iucNumber,
                    'customer_name'       =>  $customer,
                    'amount'              =>  $cable_plan->amount,
                    'balance_before'      =>  $user->account_balance,
                    'balance_after'       =>  $user->account_balance,
                    'discount'            =>  $discount
                ]);

                $data = [
                    'cablename'         =>  $transaction->cable_id,
                    'cableplan'         =>  $transaction->cable_plan_id,
                    'smart_card_number' =>  $transaction->smart_card_number
                ];

                $response = self::url(self::CABLE_URL, $data);

                self::storeApiResponse($transaction, $response);

                $amount = $transaction->amount;

                $discountedAmount = $amount;
                if (auth()->user()->isReseller()) {
                    $discountedAmount = CalculateDiscount::applyDiscount($discountedAmount, 'cable');
                }

                $discountedAmount = CalculateDiscount::calculate($discountedAmount, $discount);

                // Deduct the amount from the user's balance
                if ($user->account_balance >= $amount) {
                    $user->account_balance -= $discountedAmount;
                    $user->save();
                } else {
                    $errorResponse = [
                        'error' => 'Insufficient balance',
                        'message' => 'Your account balance is insufficient to complete this purchase.',
                    ];
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }

                if (isset($response->error)) {
                    self::$authUser->initiateRefund($discountedAmount, $transaction);
                    $errorResponse = [
                        'error'   => 'Insufficient Balance From API.',
                        'message' => "An error occurred during cable payment request. Please try again later."
                    ];
                    Log::error($errorResponse);
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }

                if (isset($response->Status) && $response->Status == 'successful') {
                    $transaction->update([
                        // 'balance_after' => $user->account_balance,
                        'api_data_id'   => $response->id ?? $response->ident
                    ]);

                    self::$authUser->initiateSuccess($discountedAmount, $transaction);

                    BeneficiaryService::create($transaction->smart_card_number, 'cable', $transaction);

                    return ApiHelper::sendResponse($transaction, "Cable subscription successful: {$transaction->cable_plan_name} for ₦{$transaction->amount} on {$transaction->customer_name} ({$transaction->smart_card_number}).");
                }

                if (isset($response->Status) && $response->Status == 'failed') {
                    $errorResponse = [
                        'error'     => 'API response Error',
                        'message'   => "Cable purchase failed. Please try again later.",
                    ];

                    self::$authUser->initiatePending($discountedAmount, $transaction);

                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }

                $errorResponse = [
                    'error'     => 'Server Error',
                    'message'   => "Oops! Unable to Perform transaction. Please try again later."
                ];

                self::$authUser->initiatePending($discountedAmount, $transaction);

                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            });
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $errorResponse = [
                'error'     =>  'Network Connection Error',
                'message'   =>  'Oops! Unable to make payment. Please check your network connection.',
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }


   
    public static function data($networkId, $typeId, $dataId, $mobileNumber)
    {
        try {
            // Start a database transaction to ensure atomicity
            return DB::transaction(function () use ($networkId, $typeId, $dataId, $mobileNumber) {
                
                // Retrieve vendor, network, plan, and type (same logic as before)
                $vendor = self::$vendor;
                $network = DataNetwork::whereVendorId($vendor->id)->whereNetworkId($networkId)->first();
                $plan = DataPlan::whereVendorId($vendor->id)->whereNetworkId($network->network_id)->whereDataId($dataId)->first();
                $type = DataType::whereVendorId($vendor->id)->whereNetworkId($network->network_id)->whereId($typeId)->first();

                // Lock the user record to prevent double spending
                $user = User::where('id', Auth::id())->lockForUpdate()->firstOrFail();

                // Check for duplicate transactions using IdempotencyCheck
                $duplicateTransaction = IdempotencyCheck::checkDuplicateTransaction(
                    DataTransaction::class, 
                    [
                        'user_id' => Auth::id(), 
                        'vendor_id' => (int) self::$vendor->id, 
                        'network_id' => (int) $network->network_id, 
                        'mobile_number' => $mobileNumber,
                        'plan_amount' => number_format($plan->amount, 2, '.', '')
                    ],
                );
                
                // Handle duplicate transactions
                if ($duplicateTransaction) {
                    return ApiHelper::sendError($duplicateTransaction, "Transaction is already pending or recently completed. Please wait.");
                }


                // Proceed with transaction logic
                $transaction = DataTransaction::create([
                    'user_id' => Auth::id(),
                    'vendor_id' => $vendor->id,
                    'network_id' => $network->network_id,
                    'type_id' => $type->id,
                    'data_id' => $plan->data_id,
                    'amount' => $plan->amount,
                    'size' => $plan->size,
                    'validity' => $plan->validity,
                    'mobile_number' => $mobileNumber,
                    'balance_before' => $user->account_balance,
                    'balance_after' => $user->account_balance,
                    'plan_network' => $network->name,
                    'plan_name' => $plan->size,
                    'plan_amount' => $plan->amount,
                    'discount' => $network->data_discount,
                ]);

                // Deduct the amount from the user's account balance
                $amount = $plan->amount;
                
                // Get Discount assign to data
                $discount = $network->data_discount;

                $discountedAmount = $amount;
                //Check is user is a Reseller
                if ($user->isReseller()) {
                    // Apply discount amount to transaction
                    $discountedAmount = CalculateDiscount::applyDiscount($discountedAmount, 'data');
                }

                $discountedAmount = CalculateDiscount::calculate($discountedAmount, $discount);

                // Handle insufficient balance
                if (! self::$authUser->verifyAccountBalance($amount))
                {
                    return response()->json([
                        'status'  => false,
                        'error' => 'Insufficient Account Balance.',
                        'message' => "You need at least ₦{$amount} to purchase this plan. Please fund your wallet to continue."
                    ], 401)->getData(); 
                }
                
                // Deduct the amount from the user's account balance
                self::$authUser->transaction($discountedAmount);

                // External API request and response processing (same as in your code)
                $data = [
                    'network' => $transaction->network_id,
                    'mobile_number' => $transaction->mobile_number,
                    'plan' => $transaction->data_id,
                    'Ported_number' => true
                ];

                $response = self::url(self::DATA_URL, $data);
                self::storeApiResponse($transaction, $response);
             
                if (isset($response->error)) {
                    // Insufficient API Wallet Balance Error
                    self::$authUser->initiateRefund($discountedAmount, $transaction);
                    $errorResponse = [
                        'error'   => 'Insufficient Balance From API.',
                        'message' => "An error occurred during Data request. Please try again later."
                    ];
                    Log::error($response->object());
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }

                if (isset($response->Status) && $response->Status == 'successful') {
                    // Update transaction after successful API response
                    $transaction->update([
                        // 'balance_after' => $user->account_balance,
                        'plan_network' => $response->plan_network,
                        'plan_name' => $response->plan_name,
                        'plan_amount' => $response->plan_amount,
                        'api_data_id' => $response->id ?? $response->ident
                    ]);

                    // Complete success processing
                    self::$authUser->initiateSuccess($discountedAmount, $transaction);
                    
                    (new ReferralService)->payReferrerForData(Auth::user(), $plan, $type, $transaction);

                    return ApiHelper::sendResponse($transaction, "Data purchase successful: {$network->name} {$plan->size} for ₦{$plan->amount} on {$mobileNumber}.");
                } else {
                    // Handle API failure response
                    $errorResponse = [
                        'error' => 'API Error',
                        'message' => 'Data purchase failed. Please try again later.',
                    ];
                    self::$authUser->initiatePending($discountedAmount, $transaction);
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }
            });  // End of DB transaction
        } catch (\Throwable $th) {
            // Log and handle errors
            Log::error($th->getMessage());
            $errorResponse = [
                'error' => 'Network Connection Error',
                'message' => 'Unable to make payment. Please check your network connection.',
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }


    public static function validateMeterNumber($meterNumber, $discoId, $meterType)
    {
        try {

            $vendor = self::$vendor;
            $meterType =  $meterType == 1 ? 'Prepaid' : 'Postpaid';

            $disco = Electricity::whereVendorId($vendor->id)->whereDiscoId($discoId)->first()->disco_name;

            $vendor = self::$vendor;

            $response = Http::withHeaders(self::headers())->get("{$vendor->api}/validatemeter/?meternumber={$meterNumber}&disconame={$disco}&mtype={$meterType}");

            $response = $response->object();

            if (!$response->invalid) {
                $responseData = [
                    'name'     => $response->name,
                    'address'  => $response->address,
                ];
                return ApiHelper::sendResponse($responseData, 'Meter Number validated. Proceed to make payment.');
            }

            $errorResponse = [
                'error'     =>   'Meter number error',
                'message'   =>   'Invalid meter number. Provide a valid meter number.',
            ];

            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $errorResponse = [
                'error'     =>  'network connection error',
                'message'   =>  'Opps! Unable to validate number. Please check your network connection.',
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }

    public static function validateIUCNumber($iucNumber, $cableName)
    {
        try {

            $cable = Cable::whereVendorId(self::$vendor->id)->whereCableId($cableName)->first()?->cable_name;

            $vendor = self::$vendor;

            $response = Http::withHeaders(self::headers())->get("{$vendor->api}/validateiuc/?smart_card_number={$iucNumber}&cablename={$cable}");

            $response = $response->object();

            if (!$response->invalid) {
                $responseData = [
                    'name'     => $response->name,
                ];
                return ApiHelper::sendResponse($responseData, 'IUC validated. Proceed to make payment.');
            }

            $errorResponse = [
                'error'     =>   'IUC/SMARTCARD error',
                'message'   =>   'Invalid IUC/SMARTCARD. Please provide a valid IUC/SMARTCARD.',
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $errorResponse = [
                'error'     =>  'network connection error',
                'message'   =>  'Opps! Unable to validate IUC/SMARTCARD. Please check your network connection.',
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }

    public static function resultChecker($examId, $quantity)
    {
        if ((int) $quantity > 5) {
            return response()->json([
                'status'    => false,
                'error'     => [],
                'message'   => "No. of pins out of range allowed. Kindly note that valid values are 1,2,3,4,5"
            ], 401)->getData();
        }

        $resultCheckerModel = ResultChecker::where('vendor_id', self::$vendor->id)->where('name', $examId)->first();
        $amount = ($quantity * $resultCheckerModel->amount);

        if (! self::$authUser->verifyAccountBalance($amount))
            return response()->json([
                'status'  => false,
                'error' => 'Insufficient Account Balance.',
                'message' => "You need at least ₦{$amount} to purchase this plan. Please fund your wallet to continue."
            ], 401)->getData();

        $resultCheckerModel = ResultChecker::where('vendor_id', self::$vendor->id)->where('name', $examId)->first();

        $transaction = ResultCheckerTransaction::create([
            'vendor_id'         =>  self::$vendor->id,
            'result_checker_id' =>  $resultCheckerModel->id,
            'exam_name'         =>  $resultCheckerModel->name,
            'quantity'          =>  $quantity,
            'amount'            =>  $amount,
            'balance_before'    =>  Auth::user()->account_balance,
            'balance_after'     =>  Auth::user()->account_balance
        ]);

        $data = [
            "exam_name"  => $resultCheckerModel->name,
            "quantity"   => $quantity
        ];

        $response = static::url(self::RESULT_CHECKER_URL, $data);

        Log::info($response);
    }

    protected static function verifyAccountBalance($amount)
    {
        if (! self::$authUser->verifyAccountBalance($amount)) {
            $errorResponse = [
                'status'    =>  false,
                'error'     =>  'Insufficient Account Balance.',
                'message'   =>  "You need at least ₦{$amount} to purchase this plan. Please fund your wallet to continue."
            ];
            return (object) $errorResponse;
        }

        return (object) [
            'status'    =>  true
        ];
    }

    public static function getDataPlans($networkId)
    {
        try {

            $url = self::$vendor->api . self::WALLET_URL;

            $response = Http::withHeaders(self::headers())->get($url);

            $response = $response->object();

            if (isset($response->Dataplans)) {

                $network = DataNetwork::find($networkId);

                $networkPlan = Str::upper($network->name) . '_PLAN';

                if (isset($response->Dataplans->$networkPlan)) {

                    $dataPlans = $response->Dataplans->$networkPlan;

                    if (isset($dataPlans->ALL)) {

                        foreach ($dataPlans->ALL as $dataPlan) {

                            $plan = DataPlan::where(['vendor_id' => self::$vendor->id, 'data_id' => $dataPlan->dataplan_id])->first();

                            if ($plan) {
                                $plan->update([
                                    'live_amount'   => $dataPlan->plan_amount,
                                    'live_size'     => $dataPlan->plan,
                                    'live_validity' => $dataPlan->month_validate,
                                ]);
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    public static function getCablePlans($cableId)
    {
        try {

            $url = self::$vendor->api . self::WALLET_URL;

            $response = Http::withHeaders(self::headers())->get($url);

            $response = $response->object();

            if (isset($response->Cableplan)) {
                $cable = Cable::find($cableId);

                $cablePlan = Str::upper($cable->cable_name) . 'PLAN';

                if (isset($response->Cableplan->$cablePlan)) {


                    $cablePlans = $response->Cableplan->$cablePlan;

                    if (is_array($cablePlans)) {
                        // dd($cablePlans);
                        foreach ($cablePlans as $cablePlan) {

                            $plan = CablePlan::where(['vendor_id' => self::$vendor->id, 'cable_plan_id' => $cablePlan->cableplan_id])->first();
                            // dd($plan);
                            if ($plan) {
                                $plan->update([
                                    'live_amount'   => $cablePlan->plan_amount,
                                    'live_package'  => $cablePlan->package,
                                ]);
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    public static function storeApiResponse($transaction, $response)
    {
        $transaction->update([
            'api_response' =>  json_encode($response)
        ]);

        return true;
    }

    public static function queryTransactionFromVendor($data, $type)
    {
        try {
            $url = [
                'airtime'        => self::AIRTIME_URL,
                'cable'          => self::CABLE_URL,
                'data'           => self::DATA_URL,
                'electricity'    => self::ELECTRICITY_URL,
                'result_checker' => self::RESULT_CHECKER_URL
            ];
            $data = json_decode($data->api_response);
            $dataId = $data->id;
            $url = self::$vendor->api . $url[$type];
            $response = Http::withHeaders(static::headers())->get($url . $dataId, []);
            return ($response->object()) ? response()->json(['status' => true, 'result' => $response->object(), 'msg' => 'Query was successful'])->getData() : NULL;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['status' => false, 'result' => [], 'msg' => 'Unable to query transaction. Please try again later.'])->getData();
        }
    }
    
}
