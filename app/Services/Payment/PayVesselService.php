<?php

namespace App\Services\Payment;

use App\Helpers\ApiHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;
use App\Models\VirtualAccount;
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

    private static function computeSHA512TransactionHash($stringifiedData, $clientSecret) 
    {
        return hash_hmac('sha512', $stringifiedData, $clientSecret);
    }

    public static function webhook(Request $request)
    {
        // Verify the webhook signature
        $payload = $request->getContent();
        $payvesselSignature = $request->header('payvessel-http-signature');
        // $ipAddress = $request->ip();
        $calculatedHash = self::computeSHA512TransactionHash($payload, config('payment.payvessel.secret'));
        // Log the raw body and hashes for debugging
        Log::info('Raw Payload: ' . $payload);
        Log::info('Computed Hash: ' . $calculatedHash);
        Log::info('PayVessel Signature: ' . $payvesselSignature);

        static::storePayload($payload);
        return;
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