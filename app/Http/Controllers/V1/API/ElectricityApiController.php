<?php

namespace App\Http\Controllers\V1\API;

use Illuminate\Http\Request;
use App\Models\Data\DataVendor;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\API\ElectricityApiRequest;
use App\Http\Requests\V1\Api\MeterApiRequest;
use App\Models\Utility\Electricity;
use App\Services\Electricity\ElectricityService;

class ElectricityApiController extends Controller
{
    protected $vendor;
    
    public function __construct(DataVendor $datavendor)
    {
        $this->vendor = $datavendor->where('status', true)->firstOrFail();
    }


    public function index()
    {
        try {

            $discos = Electricity::whereVendorId($this->vendor?->id)->whereStatus(true)->get();

            return response()->json([
                'status'   => 'success',
                'message'  => 'Electricity Disco Fetched Successfully.',
                'response' =>  $discos
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'failed', 
                'message' =>  "Unable to Electricity Disco. Try again later.",
                'error'   =>  $th->getMessage()
            ]);
        }
    }

    public function validateMeterNo(MeterApiRequest $request)
    {
        $discos = Electricity::whereVendorId($this->vendor?->id)->whereStatus(true)->whereDiscoId($request->disco_id);

        if (!$discos->exists()) {
            return response()->json([
                'status'   => 'failed',
                'message'  => 'Electricity Disco Not Found.',
                'response' =>  []
            ]); 
        }

        $electricityService = ElectricityService::validateMeterNumber($this->vendor?->id, $request->meter_number, $request->disco_id, $request->meter_type);
        return $electricityService;
    }

    public function store(ElectricityApiRequest $request)
    {
        $discos = Electricity::whereVendorId($this->vendor?->id)->whereStatus(true)->whereDiscoId($request->disco_id);

        if (!$discos->exists()) {
            return response()->json([
                'status'   => 'failed',
                'message'  => 'Electricity Disco Not Found.',
                'response' =>  []
            ]); 
        }

        $electricityTransaction = ElectricityService::create($this->vendor?->id, $request->disco_id, $request->meter_number, $request->meter_type, $request->amount, $request->owner_name, $request->phone_number, $request->owner_address);
        return $electricityTransaction;
    }
}
