<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Payment\MonnifyService;
use App\Services\Payment\Crypto\QuidaxTransferService;


class WebhookController extends Controller
{
    public function __invoke(Request $request)
    {
    	return MonnifyService::webhook($request);
    }
}
