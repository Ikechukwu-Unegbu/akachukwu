<?php

namespace App\Http\Controllers\V1\API;

use App\Models\Vendor;
use App\Helpers\ApiHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;
use App\Http\Controllers\Controller;
use App\Traits\ResolvesVendorService;

class NewtworkApiController extends Controller
{
    use ResolvesVendorService;

    public function index(Request $request)
    {
        $args = ['data', 'airtime'];

        if (!$request->query('type') || !in_array($request->query('type'), $args)) {
            return ApiHelper::sendError([], 'Network not found');
        }

        $queryNetwork = Str::lower($request->query('type'));
        
        try {
            
            // $vendor = $this->getVendorService($queryNetwork);
            $vendor = Vendor::where('status', true)->first();

            if ($request->query('type') === 'airtime') {
                $network = DataNetwork::where('vendor_id', $vendor?->id)->where('airtime_status', true)->get();
                return ApiHelper::sendResponse($network, 'Networks Fetched Successfully');
            }

            $network = DataNetwork::where('vendor_id', $vendor?->id)->where('status', true)->get();
            return ApiHelper::sendResponse($network, 'Networks Fetched Successfully');
            
        } catch (\Throwable $th) {
            return ApiHelper::sendError([$th->getMessage()], 'Unable to fetch network. Try again later');
        }
    }
}