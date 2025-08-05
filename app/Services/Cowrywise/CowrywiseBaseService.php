<?php
namespace App\Services\Cowrywise;

use App\Models\CowryWallet;
use App\Models\CowryWiseIdentity;
use App\Models\CowrywiseNextOfKin;
use App\Models\User;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CowrywiseBaseService
{
    protected const SANDBOX_URL = "https://sandbox.embed.cowrywise.com/";
    protected const PRODUCTION_URL = "https://production.embed.cowrywise.com/";

    protected const COUNTRY_CODE = "NG";
    protected const CURRENCY = "NGN";
    public static function getToken(): ?string
    {
        try {
            return Cache::remember('cowrywise_token', now()->addMinutes(50), function () {
                $response = Http::asForm()->post(self::getUrl() . 'o/token/', [
                    'grant_type' => 'client_credentials',
                    'client_id' => env('CWRY_CLIENT_ID'),
                    'client_secret' => env('CWRY_SECRET'),
                ]);

                if ($response->successful()) {
                    return $response->json()['access_token'];
                }

                logger()->error('Failed to fetch Cowrywise token', ['response' => $response->body()]);
                return null;
            });
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    protected static function formatToMultipart(array $fields): array
    {
        return collect($fields)->map(function ($value, $key) {
            return [
                'name' => $key,
                'contents' => $value,
            ];
        })->values()->toArray();
    }

    protected static function cowryWiseNewAccount(User $user, array $data)
    {
        return $user->cowryWiseAccount()->updateOrCreate(
            ['account_id' => $data['account_id']],
            [
                'account_type' => $data['account_type'],
                'account_status' => $data['account_status'],
                'is_verified' => $data['is_verified'],
                'risk_appetite' => $data['risk_appetite'],
                'account_number' => $data['account_number'],
                'is_proprietary' => $data['is_proprietary'],
                'verification_status' => $data['verification_status'],
                'date_joined' => $data['date_joined'],
                'street' => $data['address']['street'],
                'lga' => $data['address']['lga'],
                'area_code' => $data['address']['area_code'],
                'city' => $data['address']['city'],
                'state' => $data['address']['state'],
                'date_of_birth' => $data['date_of_birth'],
                'gender' => $data['gender'],
            ]
        );
    }

    protected static function cowryWiseAccountIdentity($accountId, array $data)
    {
        CowryWiseIdentity::create([
            'cowry_wise_account_id' => $accountId,
            'type' => $data['type'],
            'value' => $data['value'],
            'verification_status' => $data['verification_status'],
        ]);

        return true;
    }

    protected static function cowryWiseAccountNok($accountId, array $data)
    {
        CowrywiseNextOfKin::updateOrCreate(['cowry_wise_account_id' => $accountId], [
            'first_name' => $data['next_of_kin']['first_name'],
            'last_name' => $data['next_of_kin']['last_name'],
            'email' => $data['next_of_kin']['email'],
            'phone_number' => $data['next_of_kin']['phone_number'],
            'relationship' => $data['next_of_kin']['relationship'],
            'gender' => $data['next_of_kin']['gender'],
        ]);

        return true;
    }

    protected static function cowryWiseAccountBank($accountId, array $data)
    {
        CowrywiseNextOfKin::updateOrCreate(['cowry_wise_account_id' => $accountId], [
            'account_name' => $data['account_name'],
            'account_number' => $data['account_number'],
            'bank_name' => $data['bank_name'],
            'bank_code' => $data['bank_code'],
        ]);

        return true;
    }

    protected static function cowryWiseWallet($accountId, array $data)
    {
        foreach ($data as $d) {
            CowryWallet::updateOrCreate(['wallet_id' => $d['wallet_id']], [
                'cowry_wise_account_id' => $accountId,
                'name' => $d['name'],
                'bank_name' => $d['bank_name'],
                'product_code' => $d['product_code'],
                'currency' => $d['currency'],
                'balance' => $d['balance'],
                'account_number' => $d['account_number'],
                'account_name' => $d['account_name'],
            ]);
        }

        return true;
    }

    protected static function cowryWiseNewSavings(User $user, array $data)
    {
        return $user->savings()->create([
            'savings_id' => $data['savings_id'],
            'cowry_wise_account_id' => $user->cowryWiseAccount->id,
            'name' => $data['name'],
            'product_code' => $data['product_code'],
            'principal' => $data['principal'],
            'returns' => $data['returns'],
            'balance' => $data['balance'],
            'interest_enabled' => $data['balance'],
            'interest_rate' => $data['interest_rate'],
            'maturity_date' => $data['maturity_date'],
        ]);
    }

    protected static function causer($error, $attempt = null): void
    {
        $log = [
            'username' => Auth::user()->username ?? '',
            'Attempts' => ($attempt) ? $attempt : 'Account No. Verification (PalmPay)',
            'Error' => $error,
            'dateTime' => date('d-M-Y H:i:s A')
        ];

        Log::error(json_encode($log));
    }

    protected static function cowryWiseAccountApiCall(array $data, $accountId, string $endpointSuffix, string $successMessage, array $extraFields = [])
    {
        $requestData = array_merge($data, $extraFields);

        $token = static::getToken();

        if (!$token) {
            Log::error('Cowrywise token missing while fetching account details.');
            return ApiHelper::sendError(['Token Error!'], ['Could not retrieve API token.'], 500);
        }

        try {
            $response = Http::withToken($token)
                ->asMultipart()
                ->post(static::getUrl() . "api/v1/accounts/{$accountId}/{$endpointSuffix}", static::formatToMultipart($requestData));

            if ($response->failed()) {
                return collect([
                    'status' => false,
                    'response' => []
                ]);
            }

            return collect([
                'status' => true,
                'response' => $response->json()
            ]);

        } catch (\Throwable $th) {
            self::causer($th->getMessage(), "Exception on ({$endpointSuffix})");
            return ApiHelper::sendError(["Exception on ({$endpointSuffix})"], [$th->getMessage()], code: 500);
        }
    }

    protected static function cowryWiseGeApiCall(string $endpoint, string $successMessage, array $context = [], $payload = [])
    {
        $token = static::getToken();

        if (!$token) {
            Log::error('Cowrywise token missing while fetching account details.');
            return ApiHelper::sendError(['Token Error!'], ['Could not retrieve API token.'], 500);
        }

        try {
            $response = Http::withToken($token)
                ->asMultipart()
                ->get(static::getUrl() . $endpoint, $payload);

            if ($response->failed()) {
                Log::error("Failed to fetch {$endpoint}", array_merge([
                    'status' => $response->status(),
                    'response' => $response->json(),
                ], $context));

                return ApiHelper::sendError(["Failed to fetch {$endpoint}!"], [
                    'error' => "Failed to fetch {$endpoint}",
                    'details' => $response->json(),
                ], code: $response->status());
            }

            $data = $response->json();
            return ApiHelper::sendResponse($data, $successMessage);

        } catch (\Throwable $th) {
            Log::critical("Exception while fetching {$endpoint}", array_merge([
                'error' => $th->getMessage(),
            ], $context));

            return ApiHelper::sendError(["Exception while fetching {$endpoint}"], [$th->getMessage()], code: 500);
        }
    }

    protected static function cowryWiseApiCall(array $data, string $endpointSuffix, string $successMessage, string $errorMessage, array $extraFields = [])
    {
        $requestData = array_merge($data, $extraFields);

        $token = static::getToken();

        if (!$token) {
            Log::error('Cowrywise token missing while fetching account details.');
            return ApiHelper::sendError(['Token Error!'], ['Could not retrieve API token.'], 500);
        }

        try {
            $response = Http::withToken($token)
                ->asMultipart()
                ->post(static::getUrl() . "api/v1/{$endpointSuffix}", static::formatToMultipart($requestData));

            if ($response->failed()) {
                return collect([
                    'status' => false,
                    'response' => []
                ]);
            }


            return collect([
                'status' => true,
                'response' => $response->json()
            ]);

        } catch (\Throwable $th) {
            self::causer($th->getMessage(), "Exception on ({$endpointSuffix})");
            return ApiHelper::sendError(["Exception on ({$endpointSuffix})"], [$th->getMessage()], code: 500);
        }
    }

    protected static function getUrl()
    {
        if (app()->environment() === 'production')
            return self::PRODUCTION_URL;
        return self::SANDBOX_URL;
    }
}
