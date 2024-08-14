<?php 
namespace App\Services;

use App\Helpers\ApiHelper;
use App\Models\Data\DataTransaction;
use App\Models\Education\ResultCheckerTransaction;
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
        // $userId = request()->user()->id;
    
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
                break;
    
            case 'electricity':
                $model = \App\Models\Utility\ElectricityTransaction::class;
                break;
    
            case 'cable':
                $model = \App\Models\Utility\CableTransaction::class;
                break;
    
            case 'airtime':
                $model = \App\Models\Utility\AirtimeTransaction::class;
                break;
    
            case 'result_checker':
                $model = \App\Models\Education\ResultCheckerTransaction::class;
                break;
    
            default:
                return ApiHelper::sendError(['invalid transaction type'], 'invalid transaction type');
        }
    
        $query = $model::where('transaction_id', $id);
    
        if (in_array($type, ['data', 'airtime'])) {
            $query->with('network');
        }
    
        $transaction = $query->first();
        $transaction = $transaction->toArray();
        unset($transaction['id']);
        return $transaction;
    }


}