<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\GeneralHelpers;
use App\Models\Data\DataVendor;
use App\Models\Data\DataNetwork;
use App\Http\Controllers\Controller;
use App\Services\Airtime\AirtimeService;
use App\Services\OneSignalNotificationService;
use App\Http\Requests\V1\Api\AirtimeApiRequest;
use App\Notifications\AirtimePurchaseNotification;

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
                $request->phone_number,
                false, 
                [], 
                false, 
                null, 
                $request->wallet ?? 'base_wallet', // 👈 fallback
            );

            GeneralHelpers::sendOneSignalTransactionNotification(
                $airtimeService, 
                $airtimeService->message, 
                $request->amount, 
                AirtimePurchaseNotification::class
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
