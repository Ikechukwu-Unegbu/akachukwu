<?php

namespace App\Http\Controllers\SystemUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReferralContest;
use App\Services\Referrals\ReferralContestService;
// use App\Http\Controllers\SystemUser\Auth;
use Illuminate\Support\Facades\Auth;



class ReferralContestManagementController extends Controller
{
    protected $referralContestService;

    public function __construct(ReferralContestService $referralContestService)
    {
        $this->referralContestService = $referralContestService;
    }

    /**
     * Display all contests.
     */
    public function index()
    {
        $contests = ReferralContest::latest()->paginate(10);
        return view('system-user.referral.referral-contest-index', compact('contests'));
    }

    /**
     * Store a new referral contest.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'nullable|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date',
            'active'      => 'required|string|in:pending,active,ended'
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = ReferralContest::statusFromActiveString($validated['active']);


        ReferralContest::create($validated);

        return redirect()->back()->with('success', 'Referral contest created successfully.');
    }

    /**
     * Update an existing referral contest.
     */
    public function update(Request $request, ReferralContest $referralContest)
    {
        $validated = $request->validate([
            'name'        => 'nullable|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date',
            'active'      => 'required|string|in:pending,active,ended',
        ]);
        $validated['status'] = ReferralContest::statusFromActiveString($validated['active']);
        $referralContest->update($validated);

        return redirect()->back()->with('success', 'Referral contest updated successfully.');
    }

    /**
     * Activate a referral contest.
     */
    public function activate(ReferralContest $referralContest)
    {
        $referralContest->activate();

        return redirect()->back()->with('success', 'Referral contest activated.');
    }

    /**
     * Deactivate a referral contest.
     */
    public function deactivate(ReferralContest $referralContest)
    {
        $referralContest->deactivate();

        return redirect()->back()->with('success', 'Referral contest deactivated.');
    }

    /**
     * Delete a referral contest.
     */
    public function destroy(ReferralContest $referralContest)
    {
        $referralContest->delete();

        return redirect()->back()->with('success', 'Referral contest deleted successfully.');
    }

    /**
     * Display leaderboard for a specific referral contest.
     */
    public function leaderboard(ReferralContest $referralContest, Request $request)
    {
        try {
            // Find users in range for the contest
            $usersInRange = $this->referralContestService->findUsersInRange($referralContest->id);

            // Process referrers based on conditions
            $processedReferrers = $this->referralContestService->processReferrees($usersInRange);

            // Sort referrers by count
            $sortedReferrers = $this->referralContestService->sortReferrersByCount($processedReferrers);

            // Paginate the results
            $perPage = $request->get('per_page', 10);
            $currentPage = $request->get('page', 1);

            $totalItems = count($sortedReferrers);
            $offset = ($currentPage - 1) * $perPage;
            $paginatedReferrers = array_slice($sortedReferrers, $offset, $perPage, true);

            // Create a custom paginator
            $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $paginatedReferrers,
                $totalItems,
                $perPage,
                $currentPage,
                [
                    'path' => $request->url(),
                    'pageName' => 'page',
                ]
            );

            return view('system-user.referral.referral-contest-leaderboard', compact('referralContest', 'sortedReferrers', 'paginator'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load leaderboard: ' . $e->getMessage());
        }
    }

    // Mapping logic centralized in model (statusFromActiveString)
}
