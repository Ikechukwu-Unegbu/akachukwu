<?php

namespace App\Http\Controllers\V1\API;

use App\Http\Controllers\Controller;
use App\Services\Cowrywise\CowrywiseSavingsService;
use Illuminate\Http\Request;

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

    public function createSavings(Request $request, $accountId)
    {
        $validated = $request->validate([
            'days'  =>  'required|numeric|min:90',
            'interest_enabled'  => 'required|boolean'
        ]);

        return $this->cowrywiseSavingsService::createSavings($validated, $accountId);
    }

    public function getPerformance(Request $request, $savingId)
    {
        $validated = $request->validate([
            'start_date' =>  'required|date',
            'end_date'   => 'required|date|after:start_date',
        ]);

        return $this->cowrywiseSavingsService::getPerformance($validated, $savingId);
    }

    public function fundSavings(Request $request, $savingId)
    {
        $validated = $request->validate([
            'product_code' =>  'required|string',
            'wallet_id'    =>  'required|string',
            'amount'       => 'required|numeric'
        ]);

        return $this->cowrywiseSavingsService::fundSavings($validated, $savingId);
    }

    public function withdrawToWallet(Request $request, $savingId)
    {
        $validated = $request->validate([
            'amount'  => 'required|numeric'
        ]);

        return $this->cowrywiseSavingsService::initiateWithdrawalToWallet($validated, $savingId);
    }
}
