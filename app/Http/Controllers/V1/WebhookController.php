<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Payment\MonnifyService;

class WebhookController extends Controller
{
    public function __invoke(Request $request)
    {
    	return MonnifyService::webhook($request);
    }
}
