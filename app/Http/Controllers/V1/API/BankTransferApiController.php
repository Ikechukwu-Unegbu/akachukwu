<?php

namespace App\Http\Controllers\V1\API;

use App\Models\Bank;
use App\Helpers\ApiHelper;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\V1\API\BankAPIResource;
use App\Services\Money\PalmPayMoneyTransferService;
use App\Http\Requests\V1\Api\BankTransferAPIRequest;
use App\Http\Requests\V1\Api\QueryAccountNumberAPIRequest;

class BankTransferApiController extends Controller
{
    protected ?Bank $bank;
    protected ?ApiHelper $apiHelper;
    protected PalmPayMoneyTransferService $palmPayMoneyTransferService;
    protected $transactionFee;

    public function __construct(ApiHelper $apiHelper, Bank $bank, PalmPayMoneyTransferService $palmPayMoneyTransferService)
    {
        $this->bank = $bank;
        $this->apiHelper = $apiHelper;
        $this->palmPayMoneyTransferService =  $palmPayMoneyTransferService;
        $this->transactionFee = optional(SiteSetting::find(1))->transfer_charges ?? 50;
    }

    public function banks()
    {
        $response = $this->bank->IsPalmPay()->get();

        return $this->apiHelper::sendResponse(BankAPIResource::collection($response), 'Banks Fetched Successfully.');
    }

    public function queryAccountNumber(QueryAccountNumberAPIRequest $request)
    {
        $validatedData = $request->validated();

        $queryAccountService = $this->palmPayMoneyTransferService::queryBankAccount($validatedData['bank_code'], $validatedData['account_number']);

        return $queryAccountService;
    }

    public function processTransfer(BankTransferAPIRequest $request)
    {
        $validatedData = $request->validated();

        $accountName =  $validatedData['account_name'];
        $accountNo   =  $validatedData['account_number'];
        $bankCode    =  $validatedData['bank_code'];
        $bankId      =  $this->bank->where('code', $bankCode)->first()->id;
        $amount      =  $validatedData['amount'];
        $fee         =  $this->transactionFee;
        $remark      =  $validatedData['remark'] ?? NULL;
        $userId      =  Auth::id();

        $palmPayMoneyTransferService = $this->palmPayMoneyTransferService::processBankTransfer($accountName, $accountNo, $bankCode, $bankId, $amount, $fee, $remark, $userId);

        return $palmPayMoneyTransferService;
    }
}
