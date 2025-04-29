<?php

namespace App\Http\Controllers\V1\API;

use App\Models\Bank;
use App\Models\User;
use App\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\VirtualAccountService;
use App\Http\Requests\V1\Api\VirtualAccountRequest;

class VirtualAccountController extends Controller
{
    protected $virtualAccountService;

    public function __construct(VirtualAccountService $virtualAccountService)
    {
        $this->virtualAccountService = $virtualAccountService;
    }

    public function index()
    {
        $virtualAccounts = $this->virtualAccountService->fetchVirtualAccounts();
        return ApiHelper::sendResponse($virtualAccounts, 'Virtual Account fetched successfully');
    }

    public function store(VirtualAccountRequest $request)
    {
        try {
            $createVirtualAccount = $this->virtualAccountService->createVirtualAccount($request->validated());
            return $createVirtualAccount;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return ApiHelper::sendError($th->getMessage(), 'Unable to create Virtual Account. Please try again later');
        }
    }

    public function banks()
    {
        $banks = $this->virtualAccountService->getBanks();
        return ApiHelper::sendResponse($banks, 'Bank Codes fetched successfully');
    }

    public function createSpecificVirtualAccount(Request $request)
    {
        $request->validate([
            'providers' => 'required|array',
            'providers.*' => 'string|in:9PSB,MONIEPOINT,PALMPAY'
        ]);

        $user = User::find(auth()->id());

        $providers = $request->input('providers');

        try {

            $results = $this->virtualAccountService->generateSpecificVirtualAccount($user, $providers);

            return $results;

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create virtual accounts',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
