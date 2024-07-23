<?php

namespace App\Services\Payment;

use App\Helpers\ApiHelper;
use App\Models\Payment\PayVesselTransaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;
use App\Models\VirtualAccount;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PayVesselService 
{
    private CONST LIVE = "https://api.payvessel.com/api/";
    private CONST TEST = "https://sandbox.payvessel.com/api/";
    private CONST CREATE_VIRTUAL_ACCOUNT_URL = "external/request/customerReservedAccount/";

    private static function headers()
    {
        return [
            'Accept'        => 'application/json',
            'api-key'       => config('payment.payvessel.key'),
            'api-secret'    => 'Bearer ' . config('payment.payvessel.secret')
        ];
    }

    private static function url($url, $data = [])
    {
        $url = self::getUrl() . $url;
        
        return Http::withHeaders(self::headers())->post($url, $data);
    }

    private static function bankCodes()
    {
        return collect([
            // ["name" => "Providus Bank", "code" => 101],
            ["name" => "9Payment Service Bank", "code" => 120001]
        ]);
    }

    public static function createVirtualAccount($user)
    {        
        try {

            self::bankCodes()->each(function ($bankCode) use ($user) {
                $data = [
                    "businessid"   => config('payment.payvessel.business_id'),
                    "email"        => $user->email,
                    "name"         => $user->name,
                    "phoneNumber"  => $user->phone,
                    "bankcode"     => [$bankCode['code']],
                    "account_type" =>  "STATIC"
                ];
    
                $response = self::url(self::CREATE_VIRTUAL_ACCOUNT_URL, $data);

                if ($response->ok() === true) {
                    $response = $response->object();
                    if (isset($response->status) && $response->status) {
                        collect($response->banks)->each(function($bank) use ($user) {
                            VirtualAccount::create([
                                "reference" => $bank->trackingReference,
                                "bank_code" => $bank->bankCode,
                                "bank_name" => $bank->bankName,
                                "account_name"   => $bank->accountName,
                                "account_number" => $bank->accountNumber,
                                "account_type" => $bank->account_type,
                                "status"       => 'ACTIVE',
                                "user_id"      => $user->id,
                                "payment_id"   => self::PayVesselModal('id')
                            ]);
                        });
                    }
                }
            });

            return ApiHelper::sendResponse([], "Virtual Account Created Succeefully.");

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $errorResponse = [
                'error'    =>    "Server Error",
                'message'  =>    "Opps! Unable to create static account. Please check your network connection.",
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }       
    }

    public static function verifyKyc($bvn)
    {
        try {
            
            auth()->user()->virtualAccounts()->each(function ($account) use ($bvn) {

                $url = self::getUrl() . "external/request/virtual-account/" . config('payment.payvessel.business_id') . "/{$account->account_number}";

                $data = [
                    "bvn" => $bvn
                ];

                $response = Http::withHeaders(self::headers())->post($url, $data);

                if ($response->ok() === true) {
                    $response = $response->object();                
                    if (isset($response->status) && $response->status) {
                        self::updateAccountKyc($response->bvn);
                    }
                }

            });
            
            if (!empty(auth()->user()->bvn)) {
                return ApiHelper::sendResponse([], "BVN linked to your account successfully.");
            }

            $errorResponse = [
                'error'   => 'Invalid BVN.',
                'message' => "Invalid BVN provided."
            ];

            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $errorResponse = [
                'error'    =>    "Server Error",
                'message'  =>    "Opps! Unable to update your static account. Please check your network connection.",
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }

    }

    public static function updateAccountKyc($bvn)
    {
        auth()->user()->update(['bvn' => $bvn]);
    }

    private static function computeSHA512TransactionHash($stringifiedData, $clientSecret) 
    {
        // dd($stringifiedData);
        dd(hash_hmac('sha512', $stringifiedData, $clientSecret));
        return hash_hmac('sha512', trim($stringifiedData), $clientSecret);
    }

    public static function webhook(Request $request)
    {       
        try {
            // Verify the webhook signature
            $payload = $request->getContent();
            // Generate the filename with a timestamp
            $filename = 'webhook_payload_' . now()->format('Ymd_His') . '.txt';
            // Define the full path to the public directory
            $path = public_path($filename);
            // Save the file to the public directory
            file_put_contents($path, $payload);            

            $payvesselSignature = $request->header('payvessel-http-signature');
            $ipAddress = $request->ip();
            $calculatedHash = self::computeSHA512TransactionHash($payload, config('payment.payvessel.secret'));

            Log::info('Raw Payload: ' . $payload);
            Log::info('Computed Hash: ' . $calculatedHash);
            Log::info('Signature: ' . $payvesselSignature);
            Log::info('IP address: ' . $ipAddress);

            if (!hash_equals($calculatedHash, $payvesselSignature)) {
                return response()->json(['message' => 'Webhook payload verification failed.'], 400);
            }

            $payload = json_decode($payload);
            $paymentReference = $payload->transaction->reference;
            $transactionReference = $payload->transaction->sessionid;
            $customerEmail = $payload->customer->email;
            $amountPaid = $payload->order->amount;
            $paymentStatus = $payload->message;

            if ($paymentStatus == "Success") {

                $user = User::where('email', $customerEmail)->first();
    
                if ($user) {
    
                    if (PayVesselTransaction::where('reference_id', $paymentReference)->exists()) {
                        return response()->json(['message' => 'Payment Already Processed'], 200);
                    }
    
                    $transaction = PayVesselTransaction::create([
                        'reference_id'  => $paymentReference,
                        'trx_ref'       => $transactionReference,
                        'user_id'       => $user->id,
                        'amount'        => $amountPaid,
                        'currency'      => config('app.currency', 'NGN'),
                        'meta'          => json_encode($payload),
                        'status'        => $paymentStatus == 'Success' ? true : false
                    ]);
    
                    $user->setAccountBalance($amountPaid);
    
                    return ApiHelper::sendResponse($transaction, "Transaction successful.");
                }            
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());          
            $errorResponse = [
                'error'    =>    "Server Error",
                'message'  =>    "Opps! Unable to complete transaction at the moment.",
            ];
            return ApiHelper::sendError($errorResponse['error'], $errorResponse['message']);
        }
    }

    public static function storePayload($payload)
    {
        $filename = 'webhook_payload_' . now()->format('Ymd_His') . '.txt';
        Storage::disk('local')->put($filename, $payload);
    }

    private static function PayVesselModal($colunm)
    {
        return PaymentGateway::whereName('Payvessel')->first()->$colunm ?? NULL;
    }

    private static function getUrl()
    {
        if (app()->environment() === "production") {
            return self::LIVE;
        }

        return self::LIVE;
    }
}