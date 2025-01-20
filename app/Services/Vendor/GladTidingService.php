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
use App\Models\Utility\ElectricityTransaction;
use App\Services\Account\AccountBalanceService;
use App\Services\Beneficiary\BeneficiaryService;
use App\Models\Education\ResultCheckerTransaction;

class GladTidingService
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

        $response = Http::withHeaders(static::headers())->get($url, $data);

        return (is_object($response->object())) ? $response->object() : null;
    }

    public static function getWalletBalance()
    {
        try {
            $response = static::url(self::WALLET_URL);

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
            return DB::transaction(function () use ($networkId, $amount, $mobileNumber) {
            
                // Lock user's account balance to prevent race conditions
                $user = User::where('id', Auth::id())->lockForUpdate()->firstOrFail();
                // Retrieve network details
                $network = DataNetwork::whereVendorId(self::$vendor->id)
                    ->whereNetworkId($networkId)
                    ->firstOrFail();
                $discount = $network->airtime_discount;

                // Create the airtime transaction
                $transaction = AirtimeTransaction::create([
                    'user_id'        => Auth::id(),
                    'vendor_id'      => self::$vendor->id,
                    'network_id'     => $network->network_id,
                    'network_name'   => $network->name,
                    'amount'         => $amount,
                    'mobile_number'  => $mobileNumber,
                    // 'balance_before' => $user->account_balance,
                    // 'balance_after'  => $user->account_balance - $amount,
                    'discount'       => $discount,
                ]);

                $discountedAmount = $amount;
                if (auth()->user()->isReseller()) {
                    $discountedAmount = CalculateDiscount::applyDiscount($discountedAmount, 'airtime');
                }
                $discountedAmount = CalculateDiscount::calculate($discountedAmount, $discount);

                // Deduct the amount from the user's account balance
                $user->account_balance -= $discountedAmount;
                $user->save();

                // Prepare data for the API request
                $data = [
                    'network'       => $network->network_id,
                    'amount'        => $amount,
                    'mobile_number' => $mobileNumber,
                    'airtime_type'  => 'VTU',
                    'Ported_number' => true,
                ];

                // Call the external API
                $response = self::url(self::AIRTIME_URL, $data);

                // Store the API response
                self::storeApiResponse($transaction, $response);

                // Handle API response
                if (isset($response->error)) {
                    // Refund user if API transaction fails
                    self::$authUser->initiateRefund($discountedAmount, $transaction);
                    $errorResponse = [
                        'error'   => 'API Error',
                        'message' => 'An error occurred during the airtime request. Please try again later.',
                    ];
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }

                if (isset($response->Status) && $response->Status === 'successful') {
                    // Update transaction details on success
                    $transaction->update([
                        // 'balance_after' => $user->account_balance,
                        'api_data_id'   => $response->id ?? $response->ident,
                    ]);

                    self::$authUser->initiateSuccess($discountedAmount, $transaction);

                    // Record beneficiary information
                    BeneficiaryService::create($transaction->mobile_number, 'airtime', $transaction);

                    return ApiHelper::sendResponse($transaction, "Airtime purchase successful: ₦{$discountedAmount} {$network->name} airtime added to {$mobileNumber}.");
                }

                if (isset($response->Status) && $response->Status === 'failed') {
                    $errorResponse = [
                        'error'   => 'API Response Error',
                        'message' => 'Airtime purchase failed. Please try again later.',
                    ];
                    self::$authUser->initiatePending($discountedAmount, $transaction);
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }

                // Handle unexpected API responses
                $errorResponse = [
                    'error'   => 'Server Error',
                    'message' => 'Oops! Unable to perform the transaction. Please try again later.',
                ];
                self::$authUser->initiatePending($discountedAmount, $transaction);
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            });
        } catch (\Throwable $th) {
            // Log and handle any exceptions
            Log::error($th->getMessage());
            $errorResponse = [
                'error'   => 'Network Connection Error',
                'message' => 'Oops! Unable to make payment. Please check your network connection.',
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
                        'error'   => 'Minimum Amount Error',
                        'message' => "The minimum amount is ₦500.",
                    ];
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }
    
                // Lock user's account balance to prevent concurrent modifications
                $user = User::where('id', Auth::id())->lockForUpdate()->firstOrFail();
    
                if ($user->account_balance < $amount) {
                    $errorResponse = [
                        'error'   => 'Insufficient Balance',
                        'message' => 'Your account balance is insufficient to complete this transaction.',
                    ];
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }
    
                $vendor = self::$vendor;
    
                // Retrieve electricity provider details
                $electricity = Electricity::whereVendorId($vendor->id)->whereDiscoId($discoId)->firstOrFail();
                $discount = $electricity->discount;
    
                // Create the electricity transaction
                $transaction = ElectricityTransaction::create([
                    'user_id'                   => Auth::id(),
                    'vendor_id'                 => $vendor->id,
                    'disco_id'                  => $electricity->disco_id,
                    'disco_name'                => $electricity->disco_name,
                    'meter_number'              => $meterNumber,
                    'meter_type_id'             => $meterType,
                    'meter_type_name'           => $meterType == 1 ? 'Prepaid' : 'Postpaid',
                    'amount'                    => $amount,
                    'customer_mobile_number'    => $customerMobile,
                    'customer_name'             => $customerName,
                    'customer_address'          => $customerAddress,
                    // 'balance_before'            => $user->account_balance,
                    // 'balance_after'             => $user->account_balance - $amount,
                    'discount'                  => $discount,
                ]);

                $discountedAmount = $amount;
                if (auth()->user()->isReseller()) {
                    $discountedAmount = CalculateDiscount::applyDiscount($discountedAmount, 'electricity');
                }
                $discountedAmount = CalculateDiscount::calculate($discountedAmount, $discount);
    
                // Deduct the amount from the user's account balance
                $user->account_balance -= $discountedAmount;
                $user->save();
    
                // Prepare API request data
                $data = [
                    'disco_name'        => $transaction->disco_id,
                    'meter_number'      => $transaction->meter_number,
                    'amount'            => $amount,
                    'MeterType'         => $transaction->meter_type_name,
                    'Customer_Phone'    => $transaction->customer_mobile_number,
                    'customer_name'     => $transaction->customer_name,
                    'customer_address'  => $transaction->customer_address,
                ];
    
                // Make the external API call
                $response = self::url(self::ELECTRICITY_URL, $data);
    
                // Store the API response
                self::storeApiResponse($transaction, $response);
    
                // Handle API response
                if (isset($response->error)) {
                    // Refund user if API transaction fails
                    self::$authUser->initiateRefund($discountedAmount, $transaction);
                    $errorResponse = [
                        'error'   => 'Insufficient Balance From API',
                        'message' => 'An error occurred during bill payment request. Please try again later.',
                    ];
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }
    
                if (isset($response->Status) && $response->Status === 'successful') {
                    // Update transaction details on success
                    $transaction->update([
                        // 'balance_after' => $user->account_balance,
                        'token'         => VendorHelper::removeTokenPrefix($response->token ?? ''),
                        'api_data_id'   => $response->id ?? $response->ident,
                    ]);
    
                    self::$authUser->initiateSuccess($discountedAmount, $transaction);
    
                    // Record beneficiary information
                    BeneficiaryService::create($transaction->meter_number, 'electricity', $transaction);
    
                    return ApiHelper::sendResponse($transaction, "Bill payment successful: ₦{$transaction->amount} {$transaction->meter_type_name} for ({$transaction->meter_number}).");
                }
    
                if (isset($response->Status) && $response->Status === 'failed') {
                    $errorResponse = [
                        'error'   => 'API Response Error',
                        'message' => 'Bill purchase failed. Please try again later.',
                    ];
                    self::$authUser->initiatePending($discountedAmount, $transaction);
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }
    
                // Handle unexpected API responses
                $errorResponse = [
                    'error'   => 'Server Error',
                    'message' => 'Oops! Unable to perform the transaction. Please try again later.',
                ];
                self::$authUser->initiatePending($discountedAmount, $transaction);
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            });
        } catch (\Throwable $th) {
            // Log and handle any exceptions
            Log::error($th->getMessage());
            $errorResponse = [
                'error'   => 'Network Connection Error',
                'message' => 'Oops! Unable to make payment. Please check your network connection.',
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }
    

    public static function cable($cableId, $cablePlan, $iucNumber, $customer)
    {
        try {
            return DB::transaction(function () use ($cableId, $cablePlan, $iucNumber, $customer) {
                $vendor = self::$vendor;
    
                // Retrieve cable and plan details
                $cable = Cable::whereVendorId($vendor->id)->whereCableId($cableId)->firstOrFail();
                $cable_plan = CablePlan::whereVendorId($vendor->id)->whereCablePlanId($cablePlan)->firstOrFail();
    
                // Lock the user's balance to prevent race conditions
                $user = User::where('id', Auth::id())->lockForUpdate()->firstOrFail();
    
                // Verify sufficient account balance
                if ($user->account_balance < $cable_plan->amount) {
                    $errorResponse = [
                        'error'   => 'Insufficient Balance',
                        'message' => 'Your account balance is insufficient to complete this transaction.',
                    ];
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }
    
                $discount = $cable->discount;
    
                // Create a cable transaction record
                $transaction = CableTransaction::create([
                    'user_id'             => Auth::id(),
                    'vendor_id'           => $vendor->id,
                    'cable_name'          => $cable->cable_name,
                    'cable_id'            => $cable->cable_id,
                    'cable_plan_name'     => $cable_plan->package,
                    'cable_plan_id'       => $cable_plan->cable_plan_id,
                    'smart_card_number'   => $iucNumber,
                    'customer_name'       => $customer,
                    'amount'              => $cable_plan->amount,
                    // 'balance_before'      => $user->account_balance,
                    // 'balance_after'       => $user->account_balance,
                    'discount'            => $discount,
                ]);

                $amount = $transaction->amount;
                $discountedAmount = $amount;
                // Apply reseller or general discounts
                if (auth()->user()->isReseller()) {
                    $discountedAmount = CalculateDiscount::applyDiscount($discountedAmount, 'cable');
                }
                $discountedAmount = CalculateDiscount::calculate($discountedAmount, $discount);
    
                // Deduct the amount from the user's balance
                $user->account_balance -= $discountedAmount;
                $user->save();
    
                // Prepare data for the external API request
                $data = [
                    'cablename'         => $transaction->cable_id,
                    'cableplan'         => $transaction->cable_plan_id,
                    'smart_card_number' => $transaction->smart_card_number,
                ];
    
                // Make the external API call
                $response = self::url(self::CABLE_URL, $data);
    
                // Store the API response in the transaction
                self::storeApiResponse($transaction, $response);
    
               
    
                // Handle API response
                if (isset($response->error)) {
                    // Refund the user if the API call fails
                    self::$authUser->initiateRefund($amount, $transaction);
                    $errorResponse = [
                        'error'   => 'Insufficient Balance From API.',
                        'message' => "An error occurred during cable payment request. Please try again later.",
                    ];
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }
    
                if (isset($response->Status) && $response->Status == 'successful') {
                    // Update transaction and mark as successful
                    $transaction->update([
                        // 'balance_after' => $user->account_balance,
                        'api_data_id'   => $response->id ?? $response->ident,
                    ]);
    
                    self::$authUser->initiateSuccess($discountedAmount, $transaction);
    
                    // Record the beneficiary
                    BeneficiaryService::create($transaction->smart_card_number, 'cable', $transaction);
    
                    return ApiHelper::sendResponse($transaction, "Cable subscription successful: {$transaction->cable_plan_name} for ₦{$transaction->amount} on {$transaction->customer_name} ({$transaction->smart_card_number}).");
                }
    
                // Handle failed API response
                $errorResponse = [
                    'error'   => 'API Response Error',
                    'message' => "Cable purchase failed. Please try again later.",
                ];
                self::$authUser->initiatePending($discountedAmount, $transaction);
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            });
        } catch (\Throwable $th) {
            // Log and handle exceptions
            Log::error($th->getMessage());
            $errorResponse = [
                'error'   => 'Server Error',
                'message' => 'Opps! Unable to perform the transaction. Please try again later.',
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }
    


    public static function data($networkId, $typeId, $dataId, $mobileNumber)
    {
        try {
            return DB::transaction(function () use ($networkId, $typeId, $dataId, $mobileNumber) {
                $vendor = self::$vendor;

                // Retrieve network, plan, and type details
                $network = DataNetwork::whereVendorId($vendor->id)
                                    ->whereNetworkId($networkId)
                                    ->firstOrFail();
                $plan = DataPlan::whereVendorId($vendor->id)
                                ->whereNetworkId($network->network_id)
                                ->whereDataId($dataId)
                                ->firstOrFail();
                $type = DataType::whereVendorId($vendor->id)
                                ->whereNetworkId($network->network_id)
                                ->whereId($typeId)
                                ->firstOrFail();

                // Lock the user's balance
                $user = User::where('id', Auth::id())->lockForUpdate()->firstOrFail();

                $discount = $network->data_discount;

                // Create a data transaction record
                $transaction = DataTransaction::create([
                    'user_id'        => Auth::id(),
                    'vendor_id'      => $vendor->id,
                    'network_id'     => $network->network_id,
                    'type_id'        => $type->id,
                    'data_id'        => $plan->data_id,
                    'amount'         => $plan->amount,
                    'size'           => $plan->size,
                    'validity'       => $plan->validity,
                    'mobile_number'  => $mobileNumber,
                    // 'balance_before' => $user->account_balance,
                    // 'balance_after'  => $user->account_balance,
                    'plan_network'   => $network->name,
                    'plan_name'      => $plan->size,
                    'plan_amount'    => $plan->amount,
                    'discount'       => $discount,
                ]);

                // Apply any reseller or general discounts
                $amount = $plan->amount;
                $discountedAmount = $amount;

                if (auth()->user()->isReseller()) {
                    $discountedAmount = CalculateDiscount::applyDiscount($discountedAmount, 'data');
                }
                $discountedAmount = CalculateDiscount::calculate($discountedAmount, $discount);

                // Deduct amount from user's balance
                $user->account_balance -= $discountedAmount;
                $user->save();

                // Prepare data for external API request
                $data = [
                    'network'       => $transaction->network_id,
                    'mobile_number' => $transaction->mobile_number,
                    'plan'          => $transaction->data_id,
                    'Ported_number' => true,
                ];

                // Make the external API call
                $response = self::url(self::DATA_URL, $data);

                // Store the API response in the transaction
                self::storeApiResponse($transaction, $response);

                

                // Handle API response
                if (isset($response->error)) {
                    // Refund the user on failure
                    self::$authUser->initiateRefund($discountedAmount, $transaction);
                    $errorResponse = [
                        'error'   => 'Insufficient Balance From API',
                        'message' => "An error occurred during Data request. Please try again later.",
                    ];
                    return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
                }

                if (isset($response->Status) && $response->Status == 'successful') {
                    // Update transaction and mark as successful
                    $transaction->update([
                        // 'balance_after'     => $user->account_balance,
                        // 'status'            => true,
                        'plan_network'      => $response->plan_network ?? $network->name,
                        'plan_name'         => $response->plan_name ?? $plan->size,
                        'plan_amount'       => $response->plan_amount ?? $plan->amount,
                        'api_data_id'       => $response->id ?? $response->ident,
                    ]);

                    self::$authUser->initiateSuccess($discountedAmount, $transaction);

                    (new ReferralService)->payReferrerForData(Auth::user(), $plan, $type, $transaction);

                    // Record beneficiary
                    BeneficiaryService::create($transaction->mobile_number, 'data', $transaction);

                    return ApiHelper::sendResponse($transaction, "Data purchase successful: {$network->name} {$plan->size} for ₦{$plan->amount} on {$mobileNumber}.");
                }

                // Handle failed API response
                $errorResponse = [
                    'error'   => 'API Response Error',
                    'message' => "Data purchase failed. Please try again later.",
                ];
                self::$authUser->initiatePending($discountedAmount, $transaction);
                return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
            });
        } catch (\Throwable $th) {
            // Handle and log errors
            Log::error($th->getMessage());
            $errorResponse = [
                'error'   => 'Server Error',
                'message' => 'Opps! Unable to perform transaction. Please try again later.',
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

        if (!self::$authUser->verifyAccountBalance($amount))
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
            // 'balance_before'    =>  Auth::user()->account_balance,
            // 'balance_after'     =>  Auth::user()->account_balance
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
        if (!self::$authUser->verifyAccountBalance($amount)) {
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

            $response = static::url(self::WALLET_URL);

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
            $response = static::url(self::WALLET_URL);

            if (isset($response->Cableplan)) {

                $cable = Cable::find($cableId);

                $cablePlan = Str::upper($cable->cable_name) . 'PLAN';

                if (isset($response->Cableplan->$cablePlan)) {

                    $cablePlans = $response->Cableplan->$cablePlan;

                    if (is_array($cablePlans)) {

                        foreach ($cablePlans as $cablePlan) {

                            $plan = CablePlan::where(['vendor_id' => self::$vendor->id, 'cable_plan_id' => $cablePlan->cableplan_id])->first();

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
