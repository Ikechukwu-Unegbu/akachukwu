<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\ApiHelper;
use App\Models\Bank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\VirtualAccountRequest;
use App\Services\VirtualAccountService;

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
            if (isset($createVirtualAccount->status) && !$createVirtualAccount->status) 
                ApiHelper::sendError([], $createVirtualAccount->message);
            if (isset($createVirtualAccount->status) && $createVirtualAccount->status) 
                ApiHelper::sendResponse($createVirtualAccount, $createVirtualAccount->message);

            return ApiHelper::sendError([], "Unable to create Virtual Account. Please try again later");
        } catch (\Throwable $th) {
            return ApiHelper::sendError($th->getMessage(), 'Unable to create Virtual Account. Please try again later');
        }
    }

    public function banks()
    {
        $banks = $this->virtualAccountService->getBanks();
        return ApiHelper::sendResponse($banks, 'Bank Codes fetched successfully');
    }
}
