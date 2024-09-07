<?php

namespace App\Helpers\Admin;

class VendorHelper
{
    public static function removeTokenPrefix($string)
    {
        $prefix = "Token : ";
        if (strpos($string, $prefix) === 0) {
            return substr($string, strlen($prefix));
        }
        return $string;
    }

    public static function parseApiResponse(array $response): array
    {
        if (isset($response['response']['api_response']) && is_string($response['response']['api_response'])) {
            $response['response']['api_response'] = json_decode($response['response']['api_response'], true);
        }
        return $response;
    }
}
