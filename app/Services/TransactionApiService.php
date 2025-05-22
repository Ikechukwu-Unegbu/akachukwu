<?php
namespace App\Services;

use App\Helpers\ApiHelper;
use App\Models\Data\DataTransaction;
use App\Models\Education\ResultCheckerTransaction;
use App\Models\MoneyTransfer;
use App\Models\PalmPayTransaction;
use App\Models\Payment\Flutterwave;
use App\Models\Payment\MonnifyTransaction;
use App\Models\Payment\Paystack;
use App\Models\Payment\PayVesselTransaction;
use App\Models\Payment\VastelTransaction;
use App\Models\Utility\AirtimeTransaction;
use App\Models\Utility\CableTransaction;
use App\Models\Utility\ElectricityTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionApiService
{



    public static function getServiceModelTransactionOld($model, $userId, $startDate = null, $endDate = null)
    {

        $electricityTransactions = $model::select('transaction_id', 'status', 'amount', 'created_at')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return $electricityTransactions;
    }

    public static function getServiceModelTransaction($model, $userId, $startDate = null, $endDate = null)
    {
        $query = $model::select('transaction_id', 'status', 'amount', 'created_at')
            ->where('user_id', $userId);

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        $transactions = $query->orderBy('created_at', 'desc')->paginate(5);

        return $transactions;
    }



    public static function fetchTransactions($type = null, $startDate = null, $endDate = null)
    {
        $userId = auth()->user()->id;
        $query = DB::table(DB::raw('(
            SELECT id, transaction_id, user_id, amount, status, "data" as type, vendor_status as text_status, "debit" as transaction_type, "data purchase (debit)" as title, created_at FROM data_transactions
            UNION ALL
            SELECT id, transaction_id, user_id, amount, status, "airtime" as type, vendor_status as text_status, "debit" as transaction_type,
            "airtime purchase (debit)" as title, created_at FROM airtime_transactions
            UNION ALL
            SELECT id, transaction_id, user_id, amount, status, "cable" as type, vendor_status as text_status, "debit" as transaction_type, "cable purchase (debit)" as title, created_at FROM cable_transactions
            UNION ALL
            SELECT id, transaction_id, user_id, amount, status, "electricity" as type, vendor_status as text_status, "debit" as transaction_type, "electricity purchase (debit)" as title, created_at FROM electricity_transactions
            UNION ALL
            SELECT id, transaction_id, user_id, amount, status, "education" as type, vendor_status as text_status, "debit" as transaction_type, "exam pin purchase (debit)" as title, created_at FROM result_checker_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, user_id, amount, status, CASE WHEN type = 1 THEN "credit" ELSE "debit" END as type, api_status as text_status, CASE WHEN type = 1 THEN "credit" ELSE "debit" END as transaction_type, "wallet funding (credit)" as title, created_at FROM vastel_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, user_id, amount, status, "monnify" as type, api_status as text_status, "credit" as transaction_type, "wallet funding (credit)" as title, created_at FROM monnify_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, user_id, amount, status, "payvessle" as type, api_status as text_status, "credit" as transaction_type, "wallet funding (credit)" as title, created_at FROM pay_vessel_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, user_id, amount, status, "paystack" as type, api_status as text_status, "credit" as transaction_type, "wallet funding (credit)" as title, created_at FROM paystack_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, user_id, amount, status, "flutterwave" as type, api_status as text_status, "credit" as transaction_type, "wallet funding (credit)" as title, created_at FROM flutterwave_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, user_id, amount, status, "palmpay" as type, api_status as text_status, "credit" as transaction_type, "wallet funding (credit)" as title, created_at FROM palm_pay_transactions
            UNION ALL
            SELECT id, reference_id AS transaction_id,  user_id, amount, status, "money_transfer" AS type, transfer_status AS text_status,  CASE WHEN user_id = "' . (int) $userId . '" THEN "debit" WHEN recipient = "' . (int) $userId . '" THEN "wallet funding" ELSE "other" END AS transaction_type, CASE
                    WHEN type = "internal" THEN CONCAT("internal transfer")
                    WHEN type = "external" THEN CONCAT("bank transfer")
                END as title,
                created_at
                FROM money_transfers
            ) as transactions'))
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->select('transactions.*', 'users.name as user_name')
            ->where('transactions.user_id', $userId) // Filter by logged-in user
            ->when($type, function ($query, $type) {
                $query->where('transactions.type', $type);
            })
            ->when($startDate, function ($query, $startDate) {
                $query->where('transactions.created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                $query->where('transactions.created_at', '<=', $endDate);
            })
            ->orderBy('transactions.created_at', 'desc');

        $transactions = $query->paginate(15);

        return $transactions;

    }


    public static function getModelByCategory($category)
    {
        $models = [
            'electricity' => ElectricityTransaction::class,
            'airtime' => AirtimeTransaction::class,
            'data' => DataTransaction::class,
            'result_checker' => ResultCheckerTransaction::class,
            'cable' => CableTransaction::class,
            'money_transfer' => MoneyTransfer::class,
        ];

        return $models[strtolower($category)] ?? null;
    }




    public static function getSingleTransaction($type, $id)
    {
        switch ($type) {
            case 'data':
                $model = \App\Models\Data\DataTransaction::class;
                $idColumn = 'transaction_id';
                break;

            case 'electricity':
                $model = \App\Models\Utility\ElectricityTransaction::class;
                $idColumn = 'transaction_id';
                break;

            case 'cable':
                $model = \App\Models\Utility\CableTransaction::class;
                $idColumn = 'transaction_id';
                break;

            case 'airtime':
                $model = \App\Models\Utility\AirtimeTransaction::class;
                $idColumn = 'transaction_id';
                break;

            case 'result_checker':
                $model = \App\Models\Education\ResultCheckerTransaction::class;
                $idColumn = 'transaction_id';
                break;

            case 'paystack':
                $model = Paystack::class;
                $idColumn = 'reference_id';
                break;

            case 'flutterwave':
                $model = Flutterwave::class;
                $idColumn = 'reference_id';
                break;

            case 'payvessle':
                $model = PayVesselTransaction::class;
                $idColumn = 'reference_id';
                break;

            case 'palmpay':
                $model = PalmPayTransaction::class;
                $idColumn = 'reference_id';
                break;

            case 'monnify':
                $model = MonnifyTransaction::class;
                $idColumn = 'reference_id';
                break;

            case 'credit':
                $model = VastelTransaction::class;
                $idColumn = 'reference_id';
                break;

            case 'debit':
                $model = VastelTransaction::class;
                $idColumn = 'reference_id';
                break;

            case 'money_transfer':
                $model = MoneyTransfer::class;
                $idColumn = 'reference_id';
                break;

            default:
                return ApiHelper::sendError(['invalid transaction type'], 'invalid transaction type');
        }

        // Query the model with the appropriate ID column
        $query = $model::where($idColumn, $id);

        if (in_array($type, ['credit', 'debit'])) {
            if ($type === 'credit')
                $query->where('type', true);
            if ($type === 'debit')
                $query->where('type', false);
        }

        // Include related data for specific types
        if (in_array($type, ['data', 'airtime'])) {
            $query->with('network');
        }

        $transaction = $query->first();

        if (!$transaction) {
            return false;
        }

        $transaction = $transaction->toArray();
        unset($transaction['id']); // Remove the 'id' field from the result

        return $transaction;
    }
}
