<?php

namespace App\Services\Money;

use App\Models\Bank;
use App\Models\User;
use App\Helpers\ApiHelper;
use Illuminate\Support\Str;
use App\Models\MoneyTransfer;
use App\Helpers\GeneralHelpers;
use Illuminate\Support\Facades\DB;

class PalmPayMoneyTransferService extends BasePalmPayService
{
    public static function queryBankAccount($bankCode, $accountNo)
    {        
        try {
            $data = [
                "requestTime" =>  round(microtime(true) * 1000),
                "version"     =>  "V1.1",
                "nonceStr"    =>  Str::random(40),
                "bankCode"    =>  $bankCode,
                "bankAccNo"   =>  $accountNo,
            ];

            $response = static::processEndpoint(static::QUERY_ACCOUNT_URL, $data);

            if (isset($response->data) && isset($response->data->Status)) {
                if ($response->data->Status === 'Success') {
                    return ApiHelper::sendResponse((array) $response->data, "Account verified successfully."); 
                }
                if ($response->data->Status === 'Failed') {
                    return ApiHelper::sendError($response->data->errorMessage, "Account verification failed. Please check the details or try again later.");
                }
            }
           
            static::causer($response);            
            return ApiHelper::sendError([], "Unable to verify account at this time. Please check the details and try again later.");

        } catch (\Throwable $th) {
            static::causer($th->getMessage());     
            return ApiHelper::sendError("Server Error!", "An error occurred while verifying the account. Please try again later.");
        }
    }

    public static function processBankTransfer($accountName, $accountNo, $bankCode, $bankId, $amount, $fee, $remark, $userId)
    {
        try {
            DB::beginTransaction();
            /** Random Delay */
            GeneralHelpers::randomDelay();

            /** Lock the user record to prevent double spending */
            $user = User::where('id', $userId)->lockForUpdate()->firstOrFail();
            $totalAmount = $amount+$fee;

           // Perform all validations
            $validationResponse = static::validateTransaction($user, $totalAmount);
            if ($validationResponse) {
                return $validationResponse;
            }
            
            /** Perform Wallet Deduction from the user's balance if they have enough funds */
            $balance_before = $user->account_balance;
            $user->decrement('account_balance', $totalAmount);
            $balance_after = $user->account_balance;

            /** Check for duplicate transactions using IdempotencyCheck */
            if (static::initiateIdempotencyCheck($userId, $accountNo, $bankCode)) {
                return ApiHelper::sendError([], "Transaction is already pending or recently completed. Please wait!");
            }

            /**  Handle duplicate transactions */
            if (static::initiateLimiter($userId)) {
                return ApiHelper::sendError([], "Please Wait a moment. Last transaction still processing.");
            }

            /** Create Transaction */
            $transaction = MoneyTransfer::create([
                'trx_ref'       =>  static::generateUniqueTransactionId(),
                'amount'        =>  $amount,
                'narration'     =>  $remark,
                'bank_code'     =>  $bankCode,
                'bank_name'     =>  Bank::where('code', $bankCode)->first()?->name ?? '',
                'account_number'        =>  $accountNo,
                'sender_balance_before' =>  $balance_before,
                'sender_balance_after'  =>  $balance_after,
                'recipient_balance_before' =>  0.00,
                'recipient_balance_after'  =>  0.00,
                'transfer_status'       =>  static::ORDER_STATUS_UNPAID,
                'reference_id'          =>  static::generateUniqueReferenceId()
            ]);

            /** Prepare API Payload */
            $payload = [
                "requestTime"       => round(microtime(true) * 1000),
                "version"           => "V1.1",
                "nonceStr"          => $transaction->trx_ref,
                "orderId"           => $transaction->reference_id,
                "payeeName"         => $accountName,
                "payeeBankCode"     => $transaction->bank_code,
                "payeeBankAccNo"    => $transaction->account_number,
                "amount"            => intval(round($amount, 2) * 100),
                "currency"          => config('palmpay.country_code'),
                // "notifyUrl"         => route('webhook.palmpay'),
                "remark"            => $transaction->narration ?? 'NA'
            ];
            
            /** Store API Payload */
            $transaction->update(['meta' => $payload]);

            $response = static::processEndpoint(static::BANK_TRANSFER_URL, $payload);
            
            if (property_exists($response, 'data') && $response->data?->message === 'success') {
                $transaction->update([
                    'status'          => $response->data->status,
                    'transfer_status' => static::ORDER_STATUS_SUCCESS,
                    'api_response'    => json_encode($response),
                ]);

                DB::commit();
                return ApiHelper::sendResponse((array) $response->data, "Bank transfer successfully initiated.");
            }

            DB::commit();
            static::causer($response, 'Transaction Failed');
            return ApiHelper::sendError([], "Unable to perform bank transfer at this time. Please try again later.");
        } catch (\Throwable $th) {
            DB::rollBack();
            static::causer($th->getMessage(), 'Bank Transfer');
            return ApiHelper::sendError("Server Error!", "An error occurred while processing the transaction. Please try again later.");
        }
    }
}
