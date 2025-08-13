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
     * Get leaderboard for the active referral contest
     */
    public function leaderboard()
    {
        try {
            // Find the active contest
            $activeContest = ReferralContest::active()
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if (!$activeContest) {
                return ApiHelper::sendError([], 'No active referral contest found', 404);
            }

            // Find users in range for the contest
            $usersInRange = $this->referralContestService->findUsersInRange($activeContest->id);

            // Process referrers based on conditions
            $processedReferrers = $this->referralContestService->processReferrees($usersInRange);

            // Sort referrers by count
            $sortedReferrers = $this->referralContestService->sortReferrersByCount($processedReferrers);

            return ApiHelper::sendResponse($sortedReferrers, 'Leaderboard retrieved successfully');
        } catch (\Exception $e) {
            return ApiHelper::sendError([], 'Failed to retrieve leaderboard: ' . $e->getMessage(), 500);
        }
    }


}
