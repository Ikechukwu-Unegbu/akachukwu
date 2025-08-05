<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Cowrywise\CowrywiseOnboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        return $this->cowrywiseOnboardService::onboardingUser();
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

        return $this->cowrywiseOnboardService::updateIdentity($user);
    }

    public function updateAddress()
    {
        $user = User::findOrFail(Auth::id());

        if (!$user->cowryWiseAccount) {
            return ApiHelper::sendError(['Account does not exists'], ['Account does not exists']);
        }

        $data = [
            'street' => $user->street_address,
            'lga' => $user->local_government,
            'area_code' => $user->area_code,
            'city' => $user->city,
            'state' => $user->state,
        ];

        return $this->cowrywiseOnboardService::updateAddress($data, $user);
    }

    public function updateNextOfKin()
    {
        $user = User::findOrFail(Auth::id());

        if (!$user->cowryWiseAccount) {
            return ApiHelper::sendError(['Account does not exists'], ['Account does not exists']);
        }

        $data = [
            'relationship' => $user->next_of_kin_relationship,
            'first_name' => $user->next_of_kin_first_name,
            'last_name' => $user->next_of_kin_last_name,
            'email' => $user->next_of_kin_email,
            'phone_number' => $user->next_of_kin_phone,
            'gender' => $user->next_of_kin_gender === 'male' ? 'M' : 'F',
        ];

        return $this->cowrywiseOnboardService::updateNextOfKin($data, $user);
    }

    public function updateProfile()
    {
        $user = User::findOrFail(Auth::id());

        if (!$user->cowryWiseAccount) {
            return ApiHelper::sendError(['Account does not exists'], ['Account does not exists']);
        }

        $data = [
            'first_name' => $user->name,
            'last_name' => $user->name,
            'phone_number' => $user->phone,
            'gender' => $user->gender === 'male' ? 'M' : 'F',
            'date_of_birth' => $user->date_of_birth,
        ];

        return $this->cowrywiseOnboardService::updateProfile($data, $user);
    }

    public function addBank(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        if (!$user->cowryWiseAccount) {
            return ApiHelper::sendError(['Account does not exists'], ['Account does not exists']);
        }

        $validated = $request->validate([
            'bank_code' => 'required|string|max:3',
            'account_number' => 'required|numeric|digits:10',
        ]);

        return $this->cowrywiseOnboardService::addBank($validated, $user);
    }
}
