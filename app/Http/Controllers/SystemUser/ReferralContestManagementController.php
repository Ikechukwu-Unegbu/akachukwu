<?php

namespace App\Http\Controllers\SystemUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReferralContestManagementController extends Controller
{
     /**
     * Display all contests.
     */
    public function index()
    {
        $contests = ReferralContest::latest()->paginate(10);
        return view('admin.referral_contests.index', compact('contests'));
    }

    /**
     * Store a new referral contest.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date|before_or_equal:end_date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = 'pending';
        $validated['active'] = false;

        ReferralContest::create($validated);

        return redirect()->back()->with('success', 'Referral contest created successfully.');
    }

    /**
     * Update an existing referral contest.
     */
    public function update(Request $request, ReferralContest $referralContest)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date|before_or_equal:end_date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'status'      => 'in:pending,active,ended',
            'active'      => 'boolean',
        ]);

        $referralContest->update($validated);

        return redirect()->back()->with('success', 'Referral contest updated successfully.');
    }

    /**
     * Activate a referral contest.
     */
    public function activate(ReferralContest $referralContest)
    {
        $referralContest->update([
            'active' => true,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Referral contest activated.');
    }

    /**
     * Deactivate a referral contest.
     */
    public function deactivate(ReferralContest $referralContest)
    {
        $referralContest->update([
            'active' => false,
            'status' => 'ended',
        ]);

        return redirect()->back()->with('success', 'Referral contest deactivated.');
    }
}
