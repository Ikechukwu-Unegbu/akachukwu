<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Models\Utility\Cable;
use App\Models\Data\DataVendor;
use App\Models\Utility\CablePlan;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\API\CableApiRequest;
use App\Services\Cable\CableService;
use App\Http\Requests\V1\Api\IUCApiRequest;
use Illuminate\Support\Facades\Validator;

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
            return ApiHelper::sendResponse($cables, 'Cable Fetched Successfully.');

        } catch (\Throwable $th) {
            return ApiHelper::sendError($th->getMessage(),"Unable to fetch cable. Try again later." );
        }
    }

    public function plan(Request $request)
    {
       
        $validator= Validator::make($request->all(), [
            'cable_id'  =>  'required'
        ]);
        
        if ($validator->fails()) {
            $errors = $validator->errors();
            return ApiHelper::sendError($errors, '');
        }

        $cable_plans = CablePlan::whereVendorId($this->vendor?->id)->whereCableId($request->cable_id)->whereStatus(true);

        
        if ($cable_plans->exists()) {
            return ApiHelper::sendResponse($cable_plans, 'Cable plans fetched successfully');
        }

        return ApiHelper::sendError(['Cable plan not found'], 'Plan not found');
    }

    public function validateIUC(IUCApiRequest $request)
    {
        try {
            $cable_plan = Cable::whereVendorId($this->vendor?->id)->whereCableId($request->cable_id);

            if (!$cable_plan->exists()) {
                return ApiHelper::sendError(['Invalid IUC number'], 'Cable id is invalid');
            }

            $cableService = CableService::validateIUCNumber($this->vendor->id, $request->iuc_number, $cable_plan->first()->cable_id);

            return ApiHelper::sendResponse($cableService, 'Cable services returned');

        } catch (\Throwable $th) {
            return ApiHelper::sendError($th->getMessage(), 'Unable to purchase cable subscription');
        }
    }

    public function store(CableApiRequest $request)
    {
        $cable = Cable::whereVendorId($this->vendor?->id)->whereId($request->cable_id)->whereStatus(true)->exists();        
        $cable_plan = CablePlan::whereVendorId($this->vendor?->id)->whereCableId($request->cable_id)->whereCablePlanId($request->cable_plan_id)->exists();
        
        if (!$cable) {
            return ApiHelper::sendError(['Transaction failed'], 'Failed transaction');
            return response()->json([
                'status'   => 'failed',
                'message'  => 'Cable Id Not Found.',
                'response' =>  []
            ]);
        }

       

        $cableService = CableService::create($this->vendor->id, $request->cable_id, $request->cable_plan_id, $request->iuc_number, $request->card_owner);
        return $cableService;    
    }
}
