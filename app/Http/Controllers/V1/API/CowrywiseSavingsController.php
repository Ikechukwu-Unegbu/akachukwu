<?php

namespace App\Http\Controllers\V1\API;

use App\Models\User;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use App\Models\CowryWiseSaving;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Cowrywise\CowrywiseSavingsService;

class CowrywiseSavingsController extends Controller
{
    protected ?CowrywiseSavingsService $cowrywiseSavingsService;

    public function __construct(CowrywiseSavingsService $cowrywiseSavingsService)
    {
        $this->cowrywiseSavingsService = $cowrywiseSavingsService;
    }

    public function fetchAllSavings()
    {
        return $this->cowrywiseSavingsService::fetchAllSavings();
    }

    public function retrieveSingleSavings($savingsId)
    {
        return $this->cowrywiseSavingsService::fetchSingleSavings($savingsId);
    }

    public function getSavingRates(Request $request)
    {
        $validated = $request->validate([
            'days'  =>  'required|numeric'
        ]);

        return $this->cowrywiseSavingsService::getRates($validated);
    }

    public function createSavings(Request $request)
    {
        $validated = $request->validate([
            'days'  =>  'required|numeric|min:90',
            'interest_enabled'  => 'required|boolean'
        ]);

        $user = User::findOrFail(Auth::id());

        if (!$user->cowryWiseAccount) {
            return ApiHelper::sendError(['Account does not exists'], ['Account does not exists']);
        }

        $accountId = $user->cowryWiseAccount->account_id;

        return $this->cowrywiseSavingsService::createSavings($validated, $accountId);
    }

    public function getPerformance(Request $request, $savingsId)
    {
        $validated = $request->validate([
            'start_date' =>  'required|date',
            'end_date'   => 'required|date|after:start_date',
        ]);

        return $this->cowrywiseSavingsService::getPerformance($validated, $savingsId);
    }

    public function fundSavings(Request $request, $savingsId)
    {
        $validated = $request->validate([
            // 'product_code' =>  'required|string',
            // 'wallet_id'    =>  'required|string',
            'amount'       => 'required|numeric'
        ]);

        $user = User::findOrFail(Auth::id());

        if (!$user->cowryWiseAccount) {
            return ApiHelper::sendError(['Account does not exists'], ['Account does not exists']);
        }

        $savings = CowryWiseSaving::with(['user:id,name,username,email', 'account'])
                    ->where('savings_id', $savingsId)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        if (!$savings) {
            return ApiHelper::sendError(['Savings does not exists'], ['Savings does not exists']);
        }

        if ($user->account_balance < $validated['amount']) {
            return ApiHelper::sendError('Insufficient balance', "You dont have enough money for this transaction.");
        }

        $wallet = $user->cowryWiseAccount->wallets->first();
        $validated['product_code'] = $savings->product_code;
        $validated['wallet_id'] = $wallet->wallet_id;

        return $this->cowrywiseSavingsService::fundSavings($validated, $wallet, $savings, $user);
    }

    public function withdrawToWallet(Request $request, $savingsId)
    {
        $validated = $request->validate([
            'amount'  => 'required|numeric'
        ]);

        $user = User::findOrFail(Auth::id());

        if (!$user->cowryWiseAccount) {
            return ApiHelper::sendError(['Account does not exists'], ['Account does not exists']);
        }

        $savings = CowryWiseSaving::with(['user:id,name,username,email', 'account'])
                    ->where('savings_id', $savingsId)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        if (!$savings) {
            return ApiHelper::sendError(['Savings does not exists'], ['Savings does not exists']);
        }

        return $this->cowrywiseSavingsService::initiateWithdrawalToWallet($validated, $savingsId);
    }
}
