<?php

namespace App\Services\Cowrywise;

use App\Helpers\ApiHelper;
use App\Models\CowryWiseAccount;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CowrywiseOnboardService extends CowrywiseBaseService
{
    public static function onboardingUser(array $data)
    {
        $token = static::getToken();

        if (!$token) {
            Log::error('Cowrywise token not found or failed to generate.');
            return ApiHelper::sendError(['Token Error!'], ['Could not retrieve API token.'], 500);
        }

        try {

            $user = User::find(Auth::id());

            $response = Http::withToken($token)
                ->asMultipart()
                ->post(static::getUrl() . 'api/v1/accounts', [
                    ['name' => 'first_name', 'contents' => $data['first_name']],
                    ['name' => 'last_name', 'contents' => $data['last_name']],
                    ['name' => 'email', 'contents' => $user->email],
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

            static::cowryWiseNewAccount($user, $data['data']);

            return ApiHelper::sendResponse($data['data'], 'Account created successfully');

        } catch (\Throwable $th) {
            Log::critical('Exception while creating Cowrywise account', [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return ApiHelper::sendError(['Unexpected server error'], [$th->getMessage()], code: 500);
        }
    }

    public static function updateIdentity($data, User $user)
    {
        $response = static::cowryWiseAccountApiCall(
            $data,
            $user->cowryWiseAccount->account_id,
            'identity',
            'Identity updated successfully'
        );

        if (isset($response['status']) && $response['status']) {
            self::cowryWiseAccountIdentity($user->cowryWiseAccount->id, $response['response']['data']);
            return ApiHelper::sendResponse($response['response']['data'], "Account Identity added successfully");
        }

        return ApiHelper::sendError(["Failed to add Identity!"], [
            'error' => "Failed to update Account",
            'details' => $response,
        ], 401);
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
        $response = CowryWiseAccount::with(['banks', 'nextOfKin', 'identities'])->where('account_id', $accountId)->first();
        return ApiHelper::sendResponse($response, 'Account retrieved successfully');
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

    public static function updateAddress(array $data, User $user)
    {
        $response = static::cowryWiseAccountApiCall(
            $data,
            $user->cowryWiseAccount->account_id,
            'address',
            'Address updated successfully',
            ['country' => static::COUNTRY_CODE]
        );

        if (isset($response['status']) && $response['status']) {
            self::cowryWiseNewAccount($user, $response['response']['data']);
            return ApiHelper::sendResponse($response['response']['data'], "Account address added successfully");
        }

        return ApiHelper::sendError(["Failed to add Account address!"], [
            'error' => "Failed to update Account address",
            'details' => $response,
        ], 401);
    }

    public static function updateNextOfKin(array $data, User $user)
    {
        $response =  static::cowryWiseAccountApiCall(
            $data,
            $user->cowryWiseAccount->account_id,
            'nok',
            'Next of Kin updated successfully'
        );

        if (isset($response['status']) && $response['status']) {
            self::cowryWiseAccountNok($user->cowryWiseAccount->id, $response['response']['data']);
            return ApiHelper::sendResponse($response['response']['data'], "Account updated successfully");
        }

        return ApiHelper::sendError(["Failed to update Address!"], [
            'error' => "Failed to update Address",
            'details' => $response,
        ], 401);
    }

    public static function updateProfile(array $data, User $user)
    {
        $response =  static::cowryWiseAccountApiCall(
            $data,
            $user->cowryWiseAccount->account_id,
            'profile',
            'Account updated successfully',
            ['email' => $user->email]
        );

        if (isset($response['status']) && $response['status']) {
            self::cowryWiseNewAccount($user, $response['response']['data']);
            return ApiHelper::sendResponse($response['response']['data'], "Account updated successfully");
        }

        return ApiHelper::sendError(["Failed to update Account!"], [
            'error' => "Failed to update Account",
            'details' => $response,
        ], 401);
    }

    public static function addBank(array $data, $user)
    {
        $response = static::cowryWiseAccountApiCall(
            $data,
            $user->cowryWiseAccount->account_id,
            'bank',
            'Bank added successfully'
        );

        if (isset($response['status']) && $response['status']) {
            self::cowryWiseAccountBank($user, $response['response']['data']);
            return ApiHelper::sendResponse($response['response']['data'], "Bank added successfully");
        }

        return ApiHelper::sendError(["Failed to update bank!"], [
            'error' => "Failed to update bank",
            'details' => $response,
        ], 401);
    }
}
