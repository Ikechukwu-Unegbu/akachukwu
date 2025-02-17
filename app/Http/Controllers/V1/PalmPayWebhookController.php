<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PalmPayWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        Log::info($request);
        return true;
    }
}
