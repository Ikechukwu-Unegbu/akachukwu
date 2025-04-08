<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MtnService
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $apiKey;
    protected $tokenUrl;

    // public function __construct()
    // {
    //     $this->baseUrl = env('MTN_API_BASE_URL');
    //     $this->clientId = env('MTN_CLIENT_ID');
    //     $this->clientSecret = env('MTN_CLIENT_SECRET');
    //     $this->apiKey = env('MTN_API_KEY');
    // }

    public function __construct()
    {
        $this->clientId = env('MTN_CLIENT_ID');
        $this->clientSecret = env('MTN_CLIENT_SECRET');
        $this->tokenUrl = 'https://api.mtn.com/v1/oauth/access_token';
    }

    public function getSubscriptionPlans()
    {
        $accessToken = $this->getAccessToken();
        // dd($accessToken);

        if (!$accessToken) {
            return ['error' => 'Failed to obtain access token'];
        }

        $response = Http::withHeaders([
            'Authorization' => "Bearer $accessToken",
            'Content-Type' => 'application/json',
            'X-API-KEY' => $this->apiKey,
        ])->get("{$this->baseUrl}/subscription-plans");

        return $response->json();
    }


    // use Illuminate\Support\Facades\Http;

    public function getAccessToken()
    {
        $url = "https://api.mtn.com/oauth/client_credential/accesstoken?grant_type=client_credentials";
        
        $response = Http::asForm()->withHeaders([
            'Accept' => 'application/json',
        ])->post($url, [
            'client_id' => env('MTN_CLIENT_ID'),
            'client_secret' => env('MTN_CLIENT_SECRET'),
        ]);
    
        if ($response->successful()) {
            return $response->json()['access_token'];
            // return $response->json(); // Returns the response as an array
        }
    

        return ['error' => 'Failed to retrieve token', 'status' => $response->status()];
    }
    // public function getAccessToken()
    // {
    //     $response = Http::post("https://api.mtn.com/v1/oauth/access_token?grant_type=client_credentials&client_id=".$this->clientId."&client_secret=".$this->clientSecret, [
    //         'grant_type' => 'client_credentials',
    //         'client_id' => $this->clientId,
    //         'client_secret' => $this->clientSecret,
    //     ]);

    //     if ($response->successful()) {
    //         $data = $response->json();
    //         dd($data);
    //         return $data['access_token'] ?? null;
    //     }
    //     dd($response->json());

    //     return null;
    // }
    // Subscribe a User
    public function subscribeUser($phoneNumber, $serviceId)
    {
        $accessToken = $this->getAccessToken();
        // dd($accessToken);
        
        if (!$accessToken) {
            return ['error' => 'Failed to obtain access token'];
        }

        $response = Http::withHeaders([
            'Authorization' => "Bearer $accessToken",
            'Content-Type' => 'application/json',
            'X-API-KEY' => $this->apiKey, // If required
        ])->post("{$this->baseUrl}/subscriptions", [
            'msisdn' => $phoneNumber,
            'serviceId' => $serviceId,
        ]);

        return $response->json();
    }

    // Unsubscribe a User
    public function unsubscribeUser($phoneNumber, $subscriptionId)
    {
        $accessToken = $this->getAccessToken();
        
        if (!$accessToken) {
            return ['error' => 'Failed to obtain access token'];
        }

        $response = Http::withHeaders([
            'Authorization' => "Bearer $accessToken",
            'Content-Type' => 'application/json',
            'X-API-KEY' => $this->apiKey,
        ])->delete("{$this->baseUrl}/subscriptions/{$subscriptionId}", [
            'msisdn' => $phoneNumber,
        ]);

        return $response->json();
    }

}
