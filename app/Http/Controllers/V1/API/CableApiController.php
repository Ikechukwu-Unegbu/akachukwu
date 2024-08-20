<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Models\Utility\Cable;
use App\Models\Data\DataVendor;
use App\Models\Utility\CablePlan;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\CableApiRequest;
use App\Services\Cable\CableService;
use App\Http\Requests\V1\Api\IUCApiRequest;

class CableApiController extends Controller
{
    protected $vendor;
    
    public function __construct(DataVendor $datavendor)
    {
        $this->vendor = $datavendor->where('status', true)->firstOrFail();
    }

    public function index()
    {
        try {

            $cables = Cable::whereVendorId($this->vendor?->id)->whereStatus(true)->get();
            return ApiHelper::sendResponse( $cables, 'Cable Fetched Successfully.');    

        } catch (\Throwable $th) {
            return ApiHelper::sendError($th->getMessage(), 'Unable to fetch cable. Try again later.');
        }
    }

    public function plan(Request $request)
    {
       
        $request->validate([
            'cable_id'  =>  'required'
        ]);

        $cable_plans = CablePlan::whereVendorId($this->vendor?->id)->whereCableId($request->cable_id)->whereStatus(true);

        
        if ($cable_plans->exists()) {
            return ApiHelper::sendResponse($cable_plans->get(), 'Cable Plan Fetched Successfully.');
        }

        return ApiHelper::sendError([], 'Cable Plan Not Found.');
    }

    public function validateIUC(IUCApiRequest $request)
    {
        try {
            $cable_plan = Cable::whereVendorId($this->vendor?->id)->whereCableId($request->cable_id);

            if (!$cable_plan->exists()) {
                return ApiHelper::sendError(['Cable Id not found'], 'Cable Plan Not Found.');
            }

            $cableService = CableService::validateIUCNumber($this->vendor->id, $request->iuc_number, $cable_plan->first()->cable_id);

            return $cableService;

        } catch (\Throwable $th) {
            return ApiHelper::sendError($th->getMessage(), 'Unable to purchase cable subscription. Try again later.');
        }
    }

    public function store(CableApiRequest $request)
    {
        $cable = Cable::whereVendorId($this->vendor?->id)->whereId($request->cable_id)->whereStatus(true)->exists();        
        $cable_plan = CablePlan::whereVendorId($this->vendor?->id)->whereCableId($request->cable_id)->whereCablePlanId($request->cable_plan_id)->exists();
        
        if (!$cable) {
            return ApiHelper::sendError(['Cable Id not found'], 'Unknown cable Id');
        }

        if (!$cable_plan) {
            return ApiHelper::sendError(['Cable plan not found'], 'Unknown cable plan');
        }

        $cableService = CableService::create($this->vendor->id, $request->cable_id, $request->cable_plan_id, $request->iuc_number, $request->card_owner);
        return ApiHelper::sendResponse( $cableService, 'Cable services fetched');    
    }
    
}
