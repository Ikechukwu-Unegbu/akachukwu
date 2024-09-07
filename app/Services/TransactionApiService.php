<?php 
namespace App\Services;

use App\Helpers\ApiHelper;
use App\Models\Data\DataTransaction;
use App\Models\Education\ResultCheckerTransaction;
use App\Models\Payment\Flutterwave;
use App\Models\Payment\MonnifyTransaction;
use App\Models\Payment\Paystack;
use App\Models\Payment\PayVesselTransaction;
use App\Models\Utility\AirtimeTransaction;
use App\Models\Utility\CableTransaction;
use App\Models\Utility\ElectricityTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionApiService{

 

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
            SELECT id, transaction_id, user_id, amount, status, "data" as type, created_at FROM data_transactions
            UNION ALL
            SELECT id, transaction_id, user_id, amount, status, "airtime" as type, created_at FROM airtime_transactions
            UNION ALL
            SELECT id, transaction_id, user_id, amount, status, "cable" as type, created_at FROM cable_transactions
            UNION ALL
            SELECT id, transaction_id, user_id, amount, status, "electricity" as type, created_at FROM electricity_transactions
            UNION ALL
            SELECT id, transaction_id, user_id, amount, status, "education" as type, created_at FROM result_checker_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, user_id, amount, status, "vastel" as type, created_at FROM vastel_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, user_id, amount, status, "monnify" as type, created_at FROM monnify_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, user_id, amount, status, "payvessle" as type, created_at FROM pay_vessel_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, user_id, amount, status, "paystack" as type, created_at FROM paystack_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, user_id, amount, status, "flutterwave" as type, created_at FROM flutterwave_transactions
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

            case 'monnify':
                $model = MonnifyTransaction::class;
                $idColumn = 'reference_id';
                break;

            default:
                return ApiHelper::sendError(['invalid transaction type'], 'invalid transaction type');
        }

        // Query the model with the appropriate ID column
        $query = $model::where($idColumn, $id);

        // Include related data for specific types
        if (in_array($type, ['data', 'airtime'])) {
            $query->with('network');
        }

        $transaction = $query->first();

        if (!$transaction) {
            return ApiHelper::sendError(['transaction not found'], 'transaction not found');
        }

        $transaction = $transaction->toArray();
        unset($transaction['id']); // Remove the 'id' field from the result

        return $transaction;
    }



}