<?php
namespace App\Services\Cowrywise;

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
                Log::error("Failed to update {$endpointSuffix}", [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);

                return ApiHelper::sendError(["Failed to update {$endpointSuffix}!"], [
                    'error' => "Failed to update {$endpointSuffix}",
                    'details' => $response->json(),
                ], 401);
            }

            $data = $response->json();
            return ApiHelper::sendResponse($data, $successMessage);

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
                ->get(static::getUrl() . $endpoint, static::formatToMultipart($payload));

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
                Log::error("Failed to {$errorMessage}", [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);

                return ApiHelper::sendError(["Failed to {$errorMessage}!"], [
                    'error' => "Failed to {$errorMessage}",
                    'details' => $response->json(),
                ], 401);
            }

            $data = $response->json();
            return ApiHelper::sendResponse($data, $successMessage);

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