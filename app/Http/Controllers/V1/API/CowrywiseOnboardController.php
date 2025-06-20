<?php

namespace App\Http\Controllers\V1\API;

use App\Models\User;
use App\Helpers\ApiHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Cowrywise\CowrywiseOnboardService;

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
            'last_name' => 'required|string|max:255'
        ]);

        return $this->cowrywiseOnboardService::onboardingUser($validated);
    }

    public function retrieveSingleAccount()
    {
        $user = User::findOrFail(Auth::id());

        if (!$user->cowryWiseAccount) {
            return ApiHelper::sendError(['Account does not exists'], ['Account does not exists']);
        }

        $accountId = $user->cowryWiseAccount->account_id;

        return $this->cowrywiseOnboardService::retrieveSingleAccount($accountId);
    }

    public function getPortfolio(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        if (!$user->cowryWiseAccount) {
            return ApiHelper::sendError(['Account does not exists'], ['Account does not exists']);
        }

        $data = ['asset_id' => Str::uuid()];

        $accountId = $user->cowryWiseAccount->account_id;

        return $this->cowrywiseOnboardService::getPortfolio($data, $accountId);
    }

    public function updateIdentity(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        if (!$user->cowryWiseAccount) {
            return ApiHelper::sendError(['Account does not exists'], ['Account does not exists']);
        }

        $validated = $request->validate([
            'identity_type' => 'required|string|in:BVN,NIN',
            'identity_value' => 'required|string|max:50',
        ]);

        return $this->cowrywiseOnboardService::updateIdentity($validated, $user);
    }

    public function updateAddress(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        if (!$user->cowryWiseAccount) {
            return ApiHelper::sendError(['Account does not exists'], ['Account does not exists']);
        }

        $validated = $request->validate([
            'street'    => 'required|string',
            'lga'       => 'required|string|max:50',
            'area_code' => 'required|numeric|digits_between:2,10',
            'city'      => 'required|string|max:50',
            'state'     => 'required|string|max:50',
        ]);

        return $this->cowrywiseOnboardService::updateAddress($validated, $user);
    }

    public function updateNextOfKin(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        if (!$user->cowryWiseAccount) {
            return ApiHelper::sendError(['Account does not exists'], ['Account does not exists']);
        }

        $validated = $request->validate([
            'relationship' => 'required|string',
            'first_name'   => 'required|string|max:50',
            'last_name'    => 'required|string|max:50',
            'email'        => 'required|email|max:50',
            'phone_number' => 'required|numeric|digits:11',
            'gender'       => 'required|in:F,M',
        ]);

        return $this->cowrywiseOnboardService::updateNextOfKin($validated, $user);
    }

    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        if (!$user->cowryWiseAccount) {
            return ApiHelper::sendError(['Account does not exists'], ['Account does not exists']);
        }

        $validated = $request->validate([
            'first_name'    => 'required|string|max:50',
            'last_name'     => 'required|string|max:50',
            'phone_number'  => 'required|numeric|digits:11',
            'gender'        => 'required|in:F,M',
            'date_of_birth' => 'required|date|before:today',
        ]);

        return $this->cowrywiseOnboardService::updateProfile($validated, $user);
    }


    public function addBank(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        if (!$user->cowryWiseAccount) {
            return ApiHelper::sendError(['Account does not exists'], ['Account does not exists']);
        }

        $validated = $request->validate([
            'bank_code'      => 'required|string|max:3',
            'account_number' => 'required|numeric|digits:10',
        ]);

        return $this->cowrywiseOnboardService::addBank($validated, $user);
    }
}
