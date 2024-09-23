<?php

namespace App\Http\Controllers\V1\API;

use Illuminate\Http\Request;
use App\Models\Data\DataPlan;
use App\Models\Data\DataType;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;
use Illuminate\Validation\Rules;
use App\Services\Data\DataService;
use App\Http\Controllers\Controller;
use App\Traits\ResolvesVendorService;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\V1\Api\DataApiRequest;
use Illuminate\Validation\ValidationException;

class DataApiController extends Controller
{
    use ResolvesVendorService;
    
    protected $vendor;
    
    public function __construct()
    {
        $this->vendor = $this->getVendorService('data');
    }

    public function index(Request $request)
    {
        try {
            $request->validate([
                'network_id' => 'required|integer',
            ]);

            $dataType = DataType::whereVendorId($this->vendor->id)->whereNetworkId($request->network_id)->whereStatus(true);

            if ($dataType->exists()) {
                return response()->json([
                    'status'   => 'success',
                    'message'  => 'Data Type Fetched Successfully.',
                    'response' =>  $dataType->get()
                ]);
            }

            return response()->json([
                'status'   => 'failed',
                'message'  => 'Data Type Not Found.',
                'response' =>  []
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'failed', 
                'message' =>  "Unable to fetch data type. Try again later.",
                'error'   =>  $th->getMessage()
            ]);
        }
    }

    public function plan(Request $request)
    {        
        $request->validate([
            'network_id'    => 'required|integer',
            'data_type_id'  =>  'required|integer'
        ]);

        $dataPlans = DataPlan::with('type')->whereVendorId($this->vendor->id)->whereNetworkId($request->network_id)->whereTypeId($request->data_type_id)->whereStatus(true);

        if ($dataPlans->exists()) {
            return response()->json([
                'status'   => 'success',
                'message'  => 'Data Plan Fetched Successfully.',
                'response' =>  $dataPlans->get()
            ]);
        }

        return response()->json([
            'status'   => 'failed',
            'message'  => 'Data Plan Not Found.',
            'response' =>  []
        ]);    
    }
    
    public function store(DataApiRequest $request)
    {
        try {

            $network = DataNetwork::whereVendorId($this->vendor->id)->whereNetworkId($request->network_id)->exists();            
            $type = DataType::whereVendorId($this->vendor->id)->whereNetworkId($request->network_id)->whereId($request->data_type_id)->exists();            
            $plan = DataPlan::whereVendorId($this->vendor->id)->whereNetworkId($request->network_id)->whereId($request->plan_id);
            
            if (!$network) {
                return response()->json([
                    'status'   => 'failed',
                    'message'  => 'The Network Id provided not found.'
                ]); 
            }

            if (!$type) {
                return response()->json([
                    'status'   => 'failed',
                    'message'  => 'The Data Type Id provided not found.'
                ]); 
            }

            if (!$plan->exists()) {
                return response()->json([
                    'status'   => 'failed',
                    'message'  => 'The Data Plan Id provided not found.'
                ]); 
            }            

            $dataTransaction = DataService::create(
                $this->vendor->id, 
                $request->network_id, 
                $request->data_type_id, 
                $plan->first()->data_id, 
                $request->phone_number
            );

            return $dataTransaction;

        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'failed', 
                'message' =>  "Unable to purchase data. Try again later.",
                'error'   =>  $th->getMessage()
            ]);
        }
    }
}
