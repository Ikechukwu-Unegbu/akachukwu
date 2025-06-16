<?php

namespace App\Services\Cowrywise;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CowrywiseOnboardService extends CowrywiseBaseService
{
    public static function onboardingNewUser(array $data)
    {
        $token = static::getToken();

        if (!$token) {
            Log::error('Cowrywise token not found or failed to generate.');
            return ApiHelper::sendError(['Token Error!'], ['Could not retrieve API token.'], 500);
        }

        try {
            $response = Http::withToken($token)
                ->asMultipart()
                ->post(static::getUrl() . 'api/v1/accounts', [
                    ['name' => 'first_name', 'contents' => $data['first_name']],
                    ['name' => 'last_name', 'contents' => $data['last_name']],
                    ['name' => 'email', 'contents' => $data['email']],
                    ['name' => 'terms_of_use_accepted', 'contents' => 'True']
                ]);

            if ($response->failed()) {
                $status = $response->status();
                $errorBody = $response->json();

                return ApiHelper::sendError(['Account Creation Failed!'], [
                    'status' => $status,
                    'errorBody' => $errorBody
                ], code: $status);
            }

            $data = $response->json();

            return ApiHelper::sendResponse($data, 'Account created successfully');

        } catch (\Throwable $th) {
            Log::critical('Exception while creating Cowrywise account', [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return ApiHelper::sendError(['Unexpected server error'], [$th->getMessage()], code: 500);
        }
    }

    public static function updateIdentity($data, $accountId)
    {
        return static::cowryWiseAccountApiCall(
            $data,
            $accountId,
            'identity',
            'Identity updated successfully'
        );
    }

    public static function retrieveAllAccount()
    {
        return static::cowryWiseGeApiCall(
            'api/v1/accounts',
            'Accounts retrieved successfully'
        );
    }

    public static function retrieveSingleAccount($accountId)
    {
        return static::cowryWiseGeApiCall(
            "api/v1/accounts/{$accountId}",
            'Account details retrieved successfully',
            ['account_id' => $accountId]
        );
    }

    public static function getPortfolio($data, $accountId)
    {
        $token = static::getToken();

        if (!$token) {
            Log::error('Missing Cowrywise token while fetching portfolio');
            return ApiHelper::sendError(['Token Error!'], ['Could not retrieve API token.'], 500);
        }

        try {
            $response = Http::withToken($token)
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->send('GET', static::getUrl() . "api/v1/accounts/{$accountId}/portfolio", [
                    'body' => json_encode(['asset_id' => $data['asset_id']])
                ]);

            if ($response->failed()) {
                Log::error('Failed to fetch Cowrywise portfolio', [
                    'account_id' => $accountId,
                    'asset_id' => $data['asset_id'],
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);

                return ApiHelper::sendError(['Could not retrieve portfolio!'], [
                    'error' => 'Could not retrieve portfolio',
                    'details' => $response->json(),
                ], code: $response->status());
            }

            $response = $response->json();

            return ApiHelper::sendResponse($response, 'Portfolio fetched successfully');

        } catch (\Throwable $th) {
            Log::critical('Exception while retrieving Cowrywise portfolio', [
                'account_id' => $accountId,
                'error' => $th->getMessage(),
            ]);

            return ApiHelper::sendError(['Unexpected server error'], [$th->getMessage()], code: 500);
        }
    }

    public static function updateAddress(array $data, $accountId)
    {
        return static::cowryWiseAccountApiCall(
            $data,
            $accountId,
            'address',
            'Address updated successfully',
            ['country' => static::COUNTRY_CODE]
        );
    }

    public static function updateNextOfKin(array $data, $accountId)
    {
        return static::cowryWiseAccountApiCall(
            $data,
            $accountId,
            'nok',
            'Next of Kin updated successfully'
        );
    }

    public static function updateProfile(array $data, $accountId)
    {
        return static::cowryWiseAccountApiCall(
            $data,
            $accountId,
            'profile',
            'Profile updated successfully'
        );
    }

    public static function addBank(array $data, $accountId)
    {
        return static::cowryWiseAccountApiCall(
            $data,
            $accountId,
            'bank',
            'Bank added successfully'
        );
    }
}