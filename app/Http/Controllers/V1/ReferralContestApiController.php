<?php

namespace App\Http\Controllers\V1;

use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Models\ReferralContest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Referrals\ReferralContestService;

class ReferralContestApiController extends Controller
{
    protected $referralContestService;

    public function __construct(ReferralContestService $referralContestService)
    {
        $this->referralContestService = $referralContestService;
    }

    public function index()
    {
        try {
            $referralContests = ReferralContest::all();
            return ApiHelper::sendResponse($referralContests, 'Referral contests retrieved successfully');
        } catch (\Exception $e) {
            return ApiHelper::sendError([], 'Failed to retrieve referral contests: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get leaderboard for a specific referral contest
     */
    public function leaderboard($contestId)
    {
        try {
            // Find users in range for the contest
            $usersInRange = $this->referralContestService->findUsersInRange($contestId);

            // Process referrers based on conditions
            $processedReferrers = $this->referralContestService->processReferrees($usersInRange);

            // Sort referrers by count
            $sortedReferrers = $this->referralContestService->sortReferrersByCount($processedReferrers);

            return ApiHelper::sendResponse($sortedReferrers, 'Referrals retrieved successfully');
        } catch (\Exception $e) {
            return ApiHelper::sendError([], 'Failed to retrieve Referrals: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get currently active referral contest
     */
    public function activeContest()
    {
        try {
            $activeContest = ReferralContest::active()
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if (!$activeContest) {
                return ApiHelper::sendError([], 'No active referral contest found', 404);
            }

            return ApiHelper::sendResponse($activeContest, 'Active contest retrieved successfully');
        } catch (\Exception $e) {
            return ApiHelper::sendError([], 'Failed to retrieve active contest: ' . $e->getMessage(), 500);
        }
    }
}
