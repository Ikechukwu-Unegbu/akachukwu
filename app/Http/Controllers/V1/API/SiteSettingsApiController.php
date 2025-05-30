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

        return ApiHelper::sendResponse($siteSettings, '');
    }

    public function activeVirtualAccounts()
    {
        $activeVirtualAccounts = Bank::where('va_status', true)
            ->where('status', true)
            ->get();

        return ApiHelper::sendResponse($activeVirtualAccounts, '');
    }

}
