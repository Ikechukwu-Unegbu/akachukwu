<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Models\Data\DataVendor;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\ElectricityApiRequest;
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
            return ApiHelper::sendResponse($discos, 'Electricity Disco Fetched Successfully.');

        } catch (\Throwable $th) {
            return ApiHelper::sendError($th->getMessage(), "Unable to Electricity Disco. Try again later.");
        }
    }

    public function validateMeterNo(MeterApiRequest $request)
    {
        $discos = Electricity::whereVendorId($this->vendor?->id)->whereStatus(true)->whereDiscoId($request->disco_id);

        if (!$discos->exists()) {
            return ApiHelper::sendError([],'Invalid meter number.' );
        }

        $electricityService = ElectricityService::validateMeterNumber($this->vendor?->id, $request->meter_number, $request->disco_id, $request->meter_type);
        return $electricityService;
    }

    public function store(ElectricityApiRequest $request)
    {
        $discos = Electricity::whereVendorId($this->vendor?->id)->whereStatus(true)->whereDiscoId($request->disco_id);

        if (!$discos->exists()) {
            return ApiHelper::sendError(['Failed transaction'], 'Fialed');
        }

        $electricityTransaction = ElectricityService::create($this->vendor?->id, $request->disco_id, $request->meter_number, $request->meter_type, $request->amount, $request->owner_name, $request->phone_number, $request->owner_address);
        return $electricityTransaction;
    }
}
