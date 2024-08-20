<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\AppLogoRequest;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class AppAssetsController extends Controller
{
    public function getSpecifiedColumn(AppLogoRequest $request)
    {
        $column = $request->input('logo');

        $siteSetting = SiteSetting::select($column)->first();
        return ApiHelper::sendResponse($siteSetting->$column,'Logo fetched');
    }
    
}
