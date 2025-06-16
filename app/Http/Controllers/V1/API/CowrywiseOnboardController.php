<?php

namespace App\Http\Controllers\V1\API;

use App\Http\Controllers\Controller;
use App\Services\Cowrywise\CowrywiseOnboardService;
use Illuminate\Http\Request;

class CowrywiseOnboardController extends Controller
{
    protected ?CowrywiseOnboardService $cowrywiseOnboardService;

    public function __construct(CowrywiseOnboardService $cowrywiseOnboardService)
    {
        $this->cowrywiseOnboardService = $cowrywiseOnboardService;
    }

    public function get(Request $request)
    {
        return $this->cowrywiseOnboardService::retrieveAllAccount();
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        return $this->cowrywiseOnboardService::onboardingNewUser($validated);
    }

    public function retrieveSingleAccount($accountId)
    {
        if (!$accountId) return abort(404);

        return $this->cowrywiseOnboardService::retrieveSingleAccount($accountId);
    }

    public function getPortfolio(Request $request, $accountId)
    {
        if (!$accountId) return abort(404);

        $validated = $request->validate([
            'asset_id' => 'required|string'
        ]);

        return $this->cowrywiseOnboardService::getPortfolio($validated, $accountId);
    }

    public function updateIdentity(Request $request, $accountId)
    {
        if (!$accountId) return abort(404);

        $validated = $request->validate([
            'identity_type' => 'required|string|in:BVN,NIN',
            'identity_value' => 'required|string|max:50',
        ]);

        return $this->cowrywiseOnboardService::updateIdentity($validated, $accountId);
    }

    public function updateAddress(Request $request, $accountId)
    {
        if (!$accountId) return abort(404);

        $validated = $request->validate([
            'street'    => 'required|string',
            'lga'       => 'required|string|max:50',
            'area_code' => 'required|numeric|digits_between:2,10',
            'city'      => 'required|string|max:50',
            'state'     => 'required|string|max:50',
        ]);

        return $this->cowrywiseOnboardService::updateAddress($validated, $accountId);
    }

    public function updateNextOfKin(Request $request, $accountId)
    {
        if (!$accountId) return abort(404);

        $validated = $request->validate([
            'relationship' => 'required|string',
            'first_name'   => 'required|string|max:50',
            'last_name'    => 'required|string|max:50',
            'email'        => 'required|email|max:50',
            'phone_number' => 'required|numeric|digits:11',
            'gender'       => 'required|in:F,M',
        ]);

        return $this->cowrywiseOnboardService::updateNextOfKin($validated, $accountId);
    }

    public function updateProfile(Request $request, $accountId)
    {
        if (!$accountId) return abort(404);

        $validated = $request->validate([
            'first_name'    => 'required|string|max:50',
            'last_name'     => 'required|string|max:50',
            'email'         => 'required|email|max:50',
            'phone_number'  => 'required|numeric|digits:11',
            'gender'        => 'required|in:F,M',
            'date_of_birth' => 'required|date|before:today',
        ]);

        return $this->cowrywiseOnboardService::updateProfile($validated, $accountId);
    }

    
    public function addBank(Request $request, $accountId)
    {
        if (!$accountId) return abort(404);

        $validated = $request->validate([
            'bank_code'      => 'required|string|max:3',
            'account_number' => 'required|numeric|digits:10',
        ]);

        return $this->cowrywiseOnboardService::addBank($validated, $accountId);
    }
}
