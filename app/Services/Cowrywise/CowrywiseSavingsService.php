<?php

namespace App\Services\Cowrywise;

use App\Models\CowryWiseSaving;
use App\Models\CowryWiseSavingTransaction;
use App\Models\User;
use App\Helpers\ApiHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CowrywiseSavingsService extends CowrywiseBaseService
{
    public static function fetchAllSavings($page = 20)
    {
        // return static::cowryWiseGeApiCall(
        //     'api/v1/savings',
        //     'Savings retrieved successfully'
        // );
        $page = request()->get("page",$page);
        $savings = CowryWiseSaving::with(['user:id,name,username,email', 'account'])
                    ->latest()
                    ->paginate($page);

        return ApiHelper::sendResponse($savings, 'Savings Fetched Successfully');
    }

    public static function fetchSingleSavings($savingsId)
    {
        // return static::cowryWiseGeApiCall(
        //     "api/v1/savings/{$savingsId}",
        //     'Savings retrieved successfully'
        // );

        $savings = CowryWiseSaving::with(['user:id,name,username,email', 'account'])
                    ->where('savings_id', $savingsId)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        return ApiHelper::sendResponse($savings, 'Savings retrieved Successfully');
    }

    public static function getRates($data)
    {
        $token = static::getToken();

        if (!$token) {
            Log::error('Missing Cowrywise token while fetching Savings Rates');
            return ApiHelper::sendError(['Token Error!'], ['Could not retrieve API token.'], 500);
        }

        try {
            $response = Http::withToken($token)
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->send('POST', static::getUrl() . "api/v1/savings/rates", [
                    'body' => json_encode(['days' => $data['days']])
                ]);

            if ($response->failed()) {
                Log::error('Failed to fetch Cowrywise saving rates', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);

                return ApiHelper::sendError(['Could not saving rates!'], [
                    'error' => 'Could not saving rates',
                    'details' => $response->json(),
                ], code: $response->status());
            }

            $response = $response->json();

            return ApiHelper::sendResponse($response, 'Saving rates fetched successfully');

        } catch (\Throwable $th) {
            Log::critical('Exception while retrieving Cowrywise portfolio', [
                'error' => $th->getMessage(),
            ]);

            return ApiHelper::sendError(['Unexpected server error'], [$th->getMessage()], code: 500);
        }
    }

    public static function createSavings(array $data, $accountId)
    {
        $token = static::getToken();

        if (!$token) {
            Log::error('Cowrywise token not found or failed to generate.');
            return ApiHelper::sendError(['Token Error!'], ['Could not retrieve API token.'], 500);
        }

        try {

            $data['currency_code'] = static::CURRENCY;
            $data['account_id'] = $accountId;


            $response = Http::withToken($token)
                ->asMultipart()
                ->post(static::getUrl() . 'api/v1/savings', static::formatToMultipart($data));

            if ($response->failed()) {
                $status = $response->status();
                $errorBody = $response->json();

                return ApiHelper::sendError(['Savings Creation Failed!'], [
                    'status' => $status,
                    'errorBody' => $errorBody
                ], code: $status);
            }

            $data = $response->json();

            $user = User::find(auth()->id());

            static::cowryWiseNewSavings($user, $data['data']);

            return ApiHelper::sendResponse($data, 'Savings created successfully');

        } catch (\Throwable $th) {
            Log::critical('Exception while creating Cowrywise Savings', [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return ApiHelper::sendError(['Unexpected server error'], [$th->getMessage()], code: 500);
        }
    }

    public static function getPerformance($data, $savingsId)
    {
        return static::cowryWiseGeApiCall(
            "api/v1/savings/{$savingsId}/performance",
            'Savings Performance retrieved successfully',
            [],
            $data
        );
    }

    public static function fundSavings($data, $wallet, $saving, User $user)
    {
        $token = static::getToken();

        if (!$token) {
            Log::error('Cowrywise token not found or failed to generate.');
            return ApiHelper::sendError(['Token Error!'], ['Could not retrieve API token.'], 500);
        }

        try {

            DB::beginTransaction();

            $walletId = $data['wallet_id'];

            if ($user->account_balance < $data['amount']) {
                return ApiHelper::sendError('Insufficient balance', "You don't have enough money for this transaction.");
            }

            $user->decrement('account_balance', $data['amount']);
            $user->save();

            $response = Http::withToken($token)
                ->asMultipart()
                ->post(static::getUrl() . "api/v1/wallets/{$walletId}/transfer", static::formatToMultipart($data));

            if ($response->failed()) {
                $status = $response->status();
                $errorBody = $response->json();
                $user->increment('account_balance', $data['amount']);
                $user->save();

                return ApiHelper::sendError(['Fund Savings Creation Failed!'], [
                    'status' => $status,
                    'errorBody' => $errorBody
                ], code: $status);
            }

            $response = $response->json();
            $saving->increment('balance', $data['amount']);
            $saving->save();
            self::initiateTransaction($saving->id, $wallet->id, $response['data']);
            DB::commit();
            return ApiHelper::sendResponse($response, 'Fund Savings created successfully');

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::critical('Exception while creating Cowrywise Savings', [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return ApiHelper::sendError(['Unexpected server error'], [$th->getMessage()], code: 500);
        }
    }

    protected static function initiateTransaction($savingId, $walletId, array $data)
    {
        Log::info(json_encode($data));
        return CowryWiseSavingTransaction::create([
            'reference'   =>    $data['reference'],
            'description' =>    $data['description'],
            'amount'      =>    $data['amount']['value'],
            'cowry_wise_savings_id'   =>    $savingId,
            'cowry_wallet_id'   =>    $walletId,
            'transfer_type'     =>  $data['transfer_type'],
            'status'     =>  $data['status']
        ]);
    }

    public static function initiateWithdrawalToWallet($data, $savingId)
    {
        return static::cowryWiseApiCall(
            $data,
            "savings/{$savingId}/withdraw",
            "Withdrawal has been successfully initiated",
            "Withdraw to wallet"
        );
    }
}
