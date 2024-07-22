<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Services\Payment\PayVesselService;
use Illuminate\Http\Request;

class PayVesselWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        return PayVesselService::webhook($request);
    }
}
