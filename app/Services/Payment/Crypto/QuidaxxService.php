<?php
namespace App\Services\Payment\Crypto;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Helpers\ApiHelper;

class QuidaxxService
{
    protected $apiKey;
    protected $secretKey;
    protected $baseUrl;
    protected $isTestMode;

    public function __construct()
    {
        $this->apiKey = config('services.quidax.api_key', env('QUIDAX_API_KEY'));
        $this->secretKey = config('services.quidax.secret_key', env('QUIDAX_SECRET_KEY'));
        $this->isTestMode = config('services.quidax.test_mode', env('QUIDAX_TEST_MODE', false));
        $this->baseUrl = $this->isTestMode
            ? 'https://staging.quidax.com/api/v1'
            : 'https://app.quidax.io/api/v1';
    }

    /**
     * Get headers for API requests
     */
    protected function getHeaders()
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ];
    }


    public function getPrice($currency)
    {
        $response = $this->makeRequest('GET', "https://app.quidax.io/api/v1/markets/tickers/btcngn");
        
    }

    /**
     * Make API request with error handlingg
     */
    public function makeRequest($method, $endpoint, $data = [])
    {
        try {
            $url = $this->baseUrl . $endpoint;

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->$method($url, $data);

            $responseData = $response->json();
        

            if ($response->successful()) {
                return ApiHelper::sendResponse($responseData, 'Request successful');
            }

            // dd($response);
            Log::error('Quidax API Error', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'response' => $responseData
            ]);

            return ApiHelper::sendError(
                $responseData['errors'] ?? [],
                $responseData['message'] ?? 'API request failed'
            );

        } catch (\Exception $e) {
            Log::error('Quidax API Exception', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);

            return ApiHelper::sendError([], 'Network error: ' . $e->getMessage());
        }
    }

    /**
     * Get user account information
     */
    public function getAccountInfo()
    {
        return $this->makeRequest('get', '/users/me');
    }

    /**
     * Get user wallets
     */
    public function getUserWallets()
    {
        $user = auth()->user();
        return $this->makeRequest('get', "/users/{$user->quidax_id}/wallets");
    }

    /**
     * Get user wallets
     */
    public function getUserWalletsCurrencyAddress($currency)
    {
        $user = auth()->user();
        return $this->makeRequest('get', "/users/{$user->quidax_id}/wallets/{$currency}");
    }
    

    /**
     * Get user wallets
    */
    public function getUserWalletsAddress($currency)
    {
        $user = auth()->user();
        // dd($user->quidax_id);
        return $this->makeRequest('get', "/users/{$user->quidax_id}/wallets/{$currency}");
    }

    /**
     * Ma specific wallet balance
     */
    public function getWalletBalance($currency)
    {
        return $this->makeRequest('get', "/users/wallets/{$currency}");
    }

    /**
     * Get all available markets
     */
    public function createUser($data, $user)
    {
      
        $response = $this->makeRequest('post', '/users', $data);

        // dd($response);

        if ($response->status === true && isset($response->response->data)) {
            $quidaxUser = $response->response->data;

            $user->quidax_id          = $quidaxUser->id;
            $user->quidax_sn          = $quidaxUser->sn;
            $user->quidax_display_name= $quidaxUser->display_name;
            $user->quidax_reference   = $quidaxUser->reference;
            $user->quidax_created_at  = $quidaxUser->created_at;
            $user->quidax_updated_at  = $quidaxUser->updated_at;

            $user->save();
        }

        

        return $response;
    }



  
    /**
     * Get supported currencies
     */
    public function getSupportedCurrencies()
    {
        return $this->makeRequest('get', '/currencies');
    }

    /**
     * Get currency information
     */
    public function getCurrencyInfo($currency)
    {
        return $this->makeRequest('get', "/currencies/{$currency}");
    }

    /**
     * Initialize Quidax service (legacy method)
     */
    public function initializeQuidaxx()
    {
        return $this->getAccountInfo();
    }

    /**
     * Get account balance summary
     */
   
    public function getAccountBalanceSummary()
    {
        $wallets = $this->getUserWallets();
        // dd($wallets);
        $walletsData = $wallets->response->data;


        if (!$wallets->status) {
            return $wallets;
        }

        return ApiHelper::sendResponse(
            $walletsData,
            'Balance summary retrieved successfully'
        );
    }


}
