<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\ApiHelper;
use App\Helpers\GeneralHelpers;
use App\Http\Controllers\Controller;
use App\Models\Payment\VastelTransaction;
use App\Models\User;
use App\Services\Referrals\ApiReferralService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReferralController extends Controller
{

    public $referralService;

    public function __construct(ApiReferralService $referralService)
    {
        $this->referralService = $referralService;
    }


    public function index()
    {
        try {
           $refs = $this->referralService->getRefs();
            return ApiHelper::sendResponse($refs, 'Refs successfully fetched');
        } catch (\Exception $e) {
            Log::error('Failed to fetch referred users for user ID: ' . Auth::user()->id, [
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);
    
            return ApiHelper::sendError(['Failed to fetch referrals. Please try again later.'], 'Failed to fetch your refs');
        }
    }


    public function move_earning_to_wallet()
    {
        try {
            return DB::transaction(function () {
                
               $this->referralService->withdraw_referral_earning();
                return ApiHelper::sendResponse([], 'Your bonus has been moved to your wallet');
              });
        } catch (\Exception $e) {
            Log::error('Failed to move earnings to wallet for user ID: ' . Auth::user()->id, [
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);
    
            return ApiHelper::sendError(['Failed to move your bonus to your wallet. Please try again later.'], 'Failed');
        }
    }
}
