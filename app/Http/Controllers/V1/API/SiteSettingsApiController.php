<?php

namespace App\Http\Controllers\V1\API;

use App\Models\Bank;
use App\Helpers\ApiHelper;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SiteSettingsApiController extends Controller
{
    public function __invoke()
    {
        $siteSettings = SiteSetting::first();

        $bankConfig = is_string($siteSettings->bank_config)
            ? json_decode($siteSettings->bank_config, true)
            : $siteSettings->bank_config;

        $bankConfig = $bankConfig ?? [
            'default' => ['palm_pay' => true],
            'monnify' => []
        ];

        $response = $siteSettings->toArray();

        $response['bank_config'] = $bankConfig;


        $response['available_banks'] = Bank::whereIn('id', $bankConfig['monnify'])
            ->orWhere('code', 'PALMPAY')
            ->get()
            ->map(function ($bank) {
                return [
                    'id' => $bank->id,
                    'name' => $bank->name,
                    'code' => $bank->code,
                    'type' => $bank->type
                ];
            });

        return ApiHelper::sendResponse($response, '');
    }

}
