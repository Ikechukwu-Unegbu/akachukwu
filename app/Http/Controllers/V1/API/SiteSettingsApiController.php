<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingsApiController extends Controller
{
    public function __invoke()
    {
        $siteSettings = SiteSetting::first();
        return ApiHelper::sendResponse($siteSettings, '');
    }
}
