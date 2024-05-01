<?php

namespace App\Http\Controllers\V1\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;

class NewtworkApiController extends Controller
{
    public function index()
    {
        try {
            $vendor = DataVendor::whereStatus(true)->first();
            $network = DataNetwork::whereVendorId($vendor?->id)->whereStatus(true)->get();
            return response()->json([
                'status'    =>  'success', 
                'message'   =>  "Network Fetched Successfully",
                'response'  =>  $network
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'failed', 
                'message' =>  "Unable to fetch network. Try again later",
                'error'   =>  $th->getMessage()
            ]);
        }
    }
}
