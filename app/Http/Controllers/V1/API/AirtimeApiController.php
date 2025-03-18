<?php

namespace App\Http\Controllers\V1\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Data\DataVendor;
use App\Models\Utility\Airtime;
use App\Models\Data\DataNetwork;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Airtime\AirtimeService;
use App\Models\Utility\AirtimeTransaction;
use App\Services\OneSignalNotificationService;
use App\Http\Requests\V1\Api\AirtimeApiRequest;
use App\Services\Account\AccountBalanceService;

class AirtimeApiController extends Controller
{
    protected $vendor;
    protected $notificationService;

    public function __construct(DataVendor $datavendor, OneSignalNotificationService $notificationService)
    {
        $this->vendor = $datavendor->where('status', true)->firstOrFail();
        $this->notificationService = $notificationService;
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

            $user = Auth::user();

            $subject = ($airtimeService->status) ? "Airtime Purchase Successful" : "Airtime Purchase Failed";
            $message = ($airtimeService->status) ? "Your airtime purchase of ₦{$airtimeService?->response?->amount} was successful." : "Your airtime purchase of ₦{$request->amount} was not successful.";

            $this->notificationService->sendToUser($user, $subject, $message);

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
