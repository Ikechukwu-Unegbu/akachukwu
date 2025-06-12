<?php

namespace App\Services\Cowrywise;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CowrywiseOnboardService extends CowrywiseBaseService
{
    public static function onboardingNewUser(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $token = static::getToken();

        if (!$token) {
            Log::error('Cowrywise token not found or failed to generate.');
            return ApiHelper::sendError(['Token Error!'], ['Could not retrieve API token.'], 500);
        }

        try {
            $response = Http::withToken($token)
                ->asMultipart()
                ->post(static::getUrl() . 'api/v1/accounts', [
                    ['name' => 'first_name', 'contents' => $validated['first_name']],
                    ['name' => 'last_name', 'contents' => $validated['last_name']],
                    ['name' => 'email', 'contents' => $validated['email']],
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

    public static function updateIdentity(Request $request, $accountId)
    {
        $validated = $request->validate([
            'identity_type' => 'required|string|in:BVN,NIN',
            'identity_value' => 'required|string|max:50',
        ]);

        $token = static::getToken();

        if (!$token) {
            Log::error('Cowrywise token unavailable while updating identity');
            return ApiHelper::sendError(['Token Error!'], ['Could not retrieve API token.'], 500);
        }

        try {
            $response = Http::withToken($token)
                ->asMultipart()
                ->post(static::getUrl() . "api/v1/accounts/{$accountId}/identity", [
                    ['name' => 'identity_type', 'contents' => $validated['identity_type']],
                    ['name' => 'identity_value', 'contents' => $validated['identity_value']],
                ]);

            if ($response->failed()) {
                Log::error('Cowrywise identity update failed', [
                    'account_id' => $accountId,
                    'request' => $validated,
                    'response' => $response->json(),
                ]);

                return ApiHelper::sendError(['Account Creation Failed!'], [
                    'error' => 'Failed to update identity',
                    'details' => $response->json(),
                ], code: $response->status());
            }

            $data = $response->json();

            return ApiHelper::sendResponse($data, 'Identity updated successfully');

        } catch (\Throwable $th) {
            Log::critical('Exception while updating Cowrywise identity', [
                'account_id' => $accountId,
                'error' => $th->getMessage(),
            ]);

            return ApiHelper::sendError(['Unexpected server error'], [$th->getMessage()], code: 500);
        }
    }

    public static function retrieveAllAccount()
    {
        $token = static::getToken();

        if (!$token) {
            Log::error('Cowrywise token missing while fetching account details.');
            return ApiHelper::sendError(['Token Error!'], ['Could not retrieve API token.'], 500);
        }

        try {
            $response = Http::withToken($token)
                ->get(static::getUrl() . "api/v1/accounts");

            if ($response->failed()) {
                Log::error('Failed to fetch Cowrywise account details', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);

                return ApiHelper::sendError(['Account Creation Failed!'], [
                    'error' => 'Failed to update identity',
                    'details' => $response->json(),
                ], code: $response->status());
            }

            $data = $response->json();

            return ApiHelper::sendResponse($data, 'Accounts retrieved successfully');

        } catch (\Throwable $th) {
            Log::critical('Exception while fetching Cowrywise account', [
                'error' => $th->getMessage(),
            ]);

            return ApiHelper::sendError(['Unexpected server error'], [$th->getMessage()], code: 500);
        }
    }

    public static function retrieveSingleAccount($accountId)
    {
        if (!$accountId)
            return abort(404);

        $token = static::getToken();

        if (!$token) {
            Log::error('Cowrywise token missing while fetching account details.');
            return ApiHelper::sendError(['Token Error!'], ['Could not retrieve API token.'], 500);
        }

        try {
            $response = Http::withToken($token)
                ->get(static::getUrl() . "api/v1/accounts/{$accountId}");

            if ($response->failed()) {
                Log::error('Failed to fetch Cowrywise account details', [
                    'account_id' => $accountId,
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);

                return ApiHelper::sendError(['Failed to update identity!'], [
                    'error' => 'Failed to update identity',
                    'details' => $response->json(),
                ], code: $response->status());
            }

            $data = $response->json();

            return ApiHelper::sendResponse($data, 'Account details retrieved successfully');

        } catch (\Throwable $th) {
            Log::critical('Exception while fetching Cowrywise account', [
                'account_id' => $accountId,
                'error' => $th->getMessage(),
            ]);

            return ApiHelper::sendError(['Unexpected server error'], [$th->getMessage()], code: 500);
        }
    }

    public static function getPortfolio(Request $request, $accountId)
    {
        if (!$accountId) return abort(404);

        $validated = $request->validate([
            'asset_id' => 'required|string'
        ]);

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
                    'body' => json_encode(['asset_id' => $validated['asset_id']])
                ]);

            if ($response->failed()) {
                Log::error('Failed to fetch Cowrywise portfolio', [
                    'account_id' => $accountId,
                    'asset_id' => $validated['asset_id'],
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);

                return ApiHelper::sendError(['Could not retrieve portfolio!'], [
                    'error' => 'Could not retrieve portfolio',
                    'details' => $response->json(),
                ], code: $response->status());
            }

            $data = $response->json();

            Log::info('Cowrywise portfolio retrieved', [
                'account_id' => $accountId,
                'asset_id' => $validated['asset_id']
            ]);

            return ApiHelper::sendResponse($data, 'Portfolio fetched successfully');

        } catch (\Throwable $th) {
            Log::critical('Exception while retrieving Cowrywise portfolio', [
                'account_id' => $accountId,
                'error' => $th->getMessage(),
            ]);

            return ApiHelper::sendError(['Unexpected server error'], [$th->getMessage()], code: 500);
        }
    }
}