<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Money\PalmPayFundingService;

class PalmPayWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        return PalmPayFundingService::webhook($request);
    }
}
