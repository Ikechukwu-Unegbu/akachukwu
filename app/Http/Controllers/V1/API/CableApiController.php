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
        

        $cable_plans = CablePlan::whereVendorId($this->vendor?->id)->whereCableId($request->cable_id)->whereStatus(true);

        
        if ($cable_plans->exists()) {
            return response()->json([
                'status'   => 'success',
                'message'  => 'Cable Plan Fetched Successfully.',
                'response' =>  $cable_plans->get()
            ]);
        }

        return response()->json([
            'status'   => 'failed',
            'message'  => 'Cable Plan Not Found.',
            'response' =>  []
        ]); 
    }

    public function validateIUC(IUCApiRequest $request)
    {
        try {
            $cable_plan = Cable::whereVendorId($this->vendor?->id)->whereCableId($request->cable_id);

            if (!$cable_plan->exists()) {
                return response()->json([
                    'status'   => 'failed',
                    'message'  => 'Cable Plan Not Found.',
                    'response' =>  []
                ]); 
            }

            $cableService = CableService::validateIUCNumber($this->vendor->id, $request->iuc_number, $cable_plan->first()->cable_id);

            return $cableService;

        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'failed', 
                'message' =>  "Unable to purchase cable subscription. Try again later.",
                'error'   =>  $th->getMessage()
            ]);
        }
    }

    public function store(CableApiRequest $request)
    {
        $cable = Cable::whereVendorId($this->vendor?->id)->whereId($request->cable_id)->whereStatus(true)->exists();        
        $cable_plan = CablePlan::whereVendorId($this->vendor?->id)->whereCableId($request->cable_id)->whereCablePlanId($request->cable_plan_id)->exists();
        
        if (!$cable) {
            return response()->json([
                'status'   => 'failed',
                'message'  => 'Cable Id Not Found.',
                'response' =>  []
            ]);
        }

        if (!$cable_plan) {
            return response()->json([
                'status'   => 'failed',
                'message'  => 'Cable Plan Id Not Found.',
                'response' =>  []
            ]); 
        }

        $cableService = CableService::create($this->vendor->id, $request->cable_id, $request->cable_plan_id, $request->iuc_number, $request->card_owner);
        return $cableService;    
    }
}
