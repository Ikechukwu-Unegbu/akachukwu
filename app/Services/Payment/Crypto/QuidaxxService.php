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
            'Authorization' => 'Bearer ' . $this->secretKey,
        ];
    }

    /**
     * Make API request with error handling
     */
    protected function makeRequest($method, $endpoint, $data = [])
    {
        try {
            $url = $this->baseUrl . $endpoint;

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->$method($url, $data);

            $responseData = $response->json();
            Log::info('Quidax API Response', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'response' => $responseData
            ]);

            if ($response->successful()) {
                return ApiHelper::sendResponse($responseData, 'Request successful');
            }

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
     * Get specific wallet balance
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

        if ($response->status) {
            $user->quidax_id = $response->data->id;
            $user->quidax_sn = $response->data->sn;
            $user->quidax_display_name = $response->data->display_name;
            $user->quidax_reference = $response->data->reference;
            $user->quidax_created_at = $response->data->created_at;
            $user->quidax_updated_at = $response->data->updated_at;
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

        if (!$wallets['status']) {
            return $wallets;
        }

        $balanceSummary = [];
        foreach ($wallets['data'] as $wallet) {
            if ($wallet['balance'] > 0) {
                $balanceSummary[] = [
                    'currency' => $wallet['currency'],
                    'balance' => $wallet['balance'],
                    'locked' => $wallet['locked'] ?? 0
                ];
            }
        }

        return ApiHelper::sendResponse($balanceSummary, 'Balance summary retrieved successfully');
    }
}
