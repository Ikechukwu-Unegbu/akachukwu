<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Models\Data\DataVendor;
use App\Models\Utility\Electricity;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\ResolvesVendorService;
use App\Http\Requests\V1\Api\MeterApiRequest;
use App\Services\OneSignalNotificationService;
use App\Services\Electricity\ElectricityService;
use App\Http\Requests\V1\Api\ElectricityApiRequest;

class ElectricityApiController extends Controller
{
    use ResolvesVendorService;
    
    protected $vendor;
    protected $notificationService;

    public function __construct(OneSignalNotificationService $notificationService)
    {
        $this->vendor = $this->getVendorService('electricity');
        $this->notificationService = $notificationService;
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
        
        $user = Auth::user();

        $subject = ($electricityTransaction->status) ? "Bill Purchase Successful" : "Bill Purchase Failed";
        $message = ($electricityTransaction->status) ? "Your bill purchase of ₦{$request->amount} was successful." : "Your bill purchase of ₦{$request->amount} was not successful.";

        $this->notificationService->sendToUser($user, $subject, $message);
        
        return $electricityTransaction;
    }
}
