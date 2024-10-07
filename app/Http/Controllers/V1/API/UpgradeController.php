<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Services\UpgradeService;
use Illuminate\Http\Request;

class UpgradeController extends Controller
{
    public function store()
    {
        $upgradeService = new UpgradeService();
        $upgradeService->requestUpgrade();
        return ApiHelper::sendResponse([], 'upgrade request successful');
    }
}
