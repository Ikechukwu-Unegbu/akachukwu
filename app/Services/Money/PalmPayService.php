<?php

namespace App\Services\Money;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PalmPayService
{
    protected CONST TEST_URL = "https://open-gw-daily.palmpay-inc.com/";
    protected CONST PRODUCTION_URL = "https://open-gw-daily.palmpay-inc.com/";

    protected static function getUrl()
    {
        if (app()->environment() === 'production') 
            return self::PRODUCTION_URL;

        return self::TEST_URL;
    }

    protected static function header(string $signatures)
    {
        return [
            "Accept"        =>  "application/json",
            "Content-Type"  =>  "application/json",
            "Authorization" =>  "Bearer " . config('palmpay.app_id'),
            "Signature"     =>  $signatures,
            "CountryCode"   =>  config('palmpay.country_code'),
        ];
    }

    protected static function processEndpoint(string $url, array $data)
    {
        try {

            $signatures  = self::generateSign($data, config('palmpay.private_key'));

            $response = Http::withHeaders(self::header($signatures))->post(self::getUrl(). $url, $data);
            
            return ($response->ok()) ? $response->object() : null;

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            dd($th->getMessage());
            return $th->getMessage();
        }
    }

    public static function transfer()
    {
        // $data = [
        //     "requestTime" => round(microtime(true) * 1000),
        //     "version" => "V1.1",
        //     "nonceStr" =>  Str::random(40),
        //     "orderId" => Str::random(60),
        //     "payeeName" => "Ajibade Oluwasegun",
        //     "payeeBankCode" => "355555",
        //     "payeeBankAccNo" => "8152522525",
        //     "payeePhoneNo" => "08152522525",
        //     "amount" => 50000,
        //     "currency" => config('palmpay.country_code'),
        //     "notifyUrl" => "https://palmpay.com/notify",
        //     "remark" => ""
        // ];

        $data = [
            "requestTime" => round(microtime(true) * 1000),
            "version" => "V2.0",
            "nonceStr" => Str::random(40),
            "businessType" => 0
        ];       
        
        $url = "api/v2/general/merchant/queryBankList";

        // $url = "api/v2/merchant/payment/payout";

        $response = self::processEndpoint($url, $data);
        \Storage::put('api-response.json', json_encode($response->data, JSON_PRETTY_PRINT));

        dd($response);

        return $response;
    }


    public static function generateSign($params, $privateKey) 
    {
        // Step 1: Create the parameter string
        ksort($params); // Sort parameters by key
        $strA = '';
        foreach ($params as $key => $value) {
            if ($value !== null && $value !== '') {
                $strA .= $key . '=' . trim($value) . '&';
            }
        }
        $strA = rtrim($strA, '&'); // Remove the trailing '&'
  
        // Step 2: Generate MD5 hash and convert to uppercase
        $md5Str = strtoupper(md5($strA));
    
        // Step 3: Sign the MD5 hash with SHA1 and RSA
        $privateKey = "-----BEGIN RSA PRIVATE KEY-----\n" . chunk_split($privateKey, 64, "\n") . "-----END RSA PRIVATE KEY-----";
        openssl_sign($md5Str, $signature, $privateKey, OPENSSL_ALGO_SHA1);
        $sign = base64_encode($signature);
    
        return $sign;
    }
    
    public static function verifySign($params, $publicKey, $sign) 
    {
         // Step 1: Create the parameter string
        ksort($params); // Sort parameters by key
        $strA = '';
        foreach ($params as $key => $value) {
            if ($value !== null && $value !== '') {
                $strA .= $key . '=' . trim($value) . '&';
            }
        }
        $strA = rtrim($strA, '&'); // Remove the trailing '&'

        // Step 2: Generate MD5 hash and convert to uppercase
        $md5Str = strtoupper(md5($strA));

        // Step 3: Verify the signature
        // Ensure the public key is in PEM format
        $publicKey = "-----BEGIN PUBLIC KEY-----\n" . chunk_split($publicKey, 64, "\n") . "-----END PUBLIC KEY-----";
        $signature = base64_decode($sign);
        $result = openssl_verify($md5Str, $signature, $publicKey, OPENSSL_ALGO_SHA1);

        return $result === 1;
    }
}

// echo (new PalmPayService)->transfer();