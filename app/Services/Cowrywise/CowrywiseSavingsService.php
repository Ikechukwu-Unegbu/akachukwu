<?php

namespace App\Services\Cowrywise;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CowrywiseSavingsService extends CowrywiseBaseService
{
    public static function fetchAllSavings($page = 10)
    {
        return static::cowryWiseGeApiCall(
            'api/v1/savings',
            'Savings retrieved successfully'
        );
    }

    public static function fetchSingleSavings($savingsId)
    {
        return static::cowryWiseGeApiCall(
            "api/v1/savings/{$savingsId}",
            'Savings retrieved successfully'
        );
    }

    public static function getRates($data)
    {
        $token = static::getToken();

        if (!$token) {
            Log::error('Missing Cowrywise token while fetching Savings Rates');
            return ApiHelper::sendError(['Token Error!'], ['Could not retrieve API token.'], 500);
        }

        try {
            $response = Http::withToken($token)
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->send('POST', static::getUrl() . "api/v1/savings/rates", [
                    'body' => json_encode(['days' => $data['days']])
                ]);

            if ($response->failed()) {
                Log::error('Failed to fetch Cowrywise saving rates', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);

                return ApiHelper::sendError(['Could not saving rates!'], [
                    'error' => 'Could not saving rates',
                    'details' => $response->json(),
                ], code: $response->status());
            }

            $response = $response->json();

            return ApiHelper::sendResponse($response, 'Saving rates fetched successfully');

        } catch (\Throwable $th) {
            Log::critical('Exception while retrieving Cowrywise portfolio', [
                'error' => $th->getMessage(),
            ]);

            return ApiHelper::sendError(['Unexpected server error'], [$th->getMessage()], code: 500);
        }
    }

    public static function createSavings(array $data, $accountId)
    {
        $token = static::getToken();

        if (!$token) {
            Log::error('Cowrywise token not found or failed to generate.');
            return ApiHelper::sendError(['Token Error!'], ['Could not retrieve API token.'], 500);
        }

        try {

            $data['currency_code'] = static::CURRENCY;
            $data['account_id'] = $accountId;


            $response = Http::withToken($token)
                ->asMultipart()
                ->post(static::getUrl() . 'api/v1/savings', static::formatToMultipart($data));

            if ($response->failed()) {
                $status = $response->status();
                $errorBody = $response->json();

                return ApiHelper::sendError(['Savings Creation Failed!'], [
                    'status' => $status,
                    'errorBody' => $errorBody
                ], code: $status);
            }

            $data = $response->json();

            return ApiHelper::sendResponse($data, 'Savings created successfully');

        } catch (\Throwable $th) {
            Log::critical('Exception while creating Cowrywise Savings', [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return ApiHelper::sendError(['Unexpected server error'], [$th->getMessage()], code: 500);
        }
    }

    public static function getPerformance($data, $savingsId)
    {
        return static::cowryWiseGeApiCall(
            "api/v1/savings/{$savingsId}/performance",
            'Savings Performance retrieved successfully',
            [],
            $data
        );
    }

    public static function fundSavings($data, $savingId)
    {
        $token = static::getToken();

        if (!$token) {
            Log::error('Cowrywise token not found or failed to generate.');
            return ApiHelper::sendError(['Token Error!'], ['Could not retrieve API token.'], 500);
        }

        try {

            $walletId = $data['wallet_id'];

            $response = Http::withToken($token)
                ->asMultipart()
                ->post(static::getUrl() . "api/v1/wallets/{$walletId}/transfer", static::formatToMultipart($data));

            if ($response->failed()) {
                $status = $response->status();
                $errorBody = $response->json();

                return ApiHelper::sendError(['Fund Savings Creation Failed!'], [
                    'status' => $status,
                    'errorBody' => $errorBody
                ], code: $status);
            }

            $data = $response->json();

            return ApiHelper::sendResponse($data, 'Fund Savings created successfully');

        } catch (\Throwable $th) {
            Log::critical('Exception while creating Cowrywise Savings', [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return ApiHelper::sendError(['Unexpected server error'], [$th->getMessage()], code: 500);
        }
    }

    public static function initiateWithdrawalToWallet($data, $savingId)
    {
        return static::cowryWiseApiCall(
            $data,
            "savings/{$savingId}/withdraw",
            "Withdrawal has been successfully initiated",
            "Withdraw to wallet"
        );
    }
}