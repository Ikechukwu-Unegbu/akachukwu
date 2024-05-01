<?php

namespace App\Http\Controllers\V1\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;
use App\Services\Airtime\AirtimeService;
use App\Models\Utility\AirtimeTransaction;
use App\Services\Account\AccountBalanceService;
use App\Http\Requests\V1\Api\AirtimeApiRequest;
use App\Models\User;
use App\Models\Utility\Airtime;
use Illuminate\Support\Facades\Auth;

class AirtimeApiController extends Controller
{
    protected $vendor;

    public function __construct(DataVendor $datavendor)
    {
        $this->vendor = $datavendor->where('status', true)->firstOrFail();
    }

    public function store(AirtimeApiRequest $request)
    {
        try {
            $network = DataNetwork::whereVendorId($this->vendor?->id)->whereNetworkId($request->network_id)->exists();

            if (!$network) {
                return response()->json([
                    'status'   => 'failed',
                    'message'  => 'The Network Id provided not found.'
                ]);
            }

            $airtimeService = AirtimeService::create(
                $this->vendor->id,
                $request->network_id,
                $request->amount,
                $request->phone_number
            );

            return $airtimeService;
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'failed', 
                'message' =>  "Unable to purchase airtime. Try again later.",
                'error'   =>  $th->getMessage()
            ]);
        }
    }
}
