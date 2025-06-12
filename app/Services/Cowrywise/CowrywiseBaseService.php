<?php
namespace App\Services\Cowrywise;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CowrywiseBaseService
{
    protected const SANDBOX_URL = "https://sandbox.embed.cowrywise.com/";
    protected const PRODUCTION_URL = "https://production.embed.cowrywise.com/";


    public static function getToken() : ?string
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

    protected static function getUrl()
    {
        if (app()->environment() === 'production')
            return self::PRODUCTION_URL;
        return self::SANDBOX_URL;
    }
}