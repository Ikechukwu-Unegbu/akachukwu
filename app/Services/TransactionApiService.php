<?php 
namespace App\Services;

use App\Helpers\ApiHelper;
use App\Models\Utility\ElectricityTransaction;

class TransactionApiService{

 

    public static function getServiceModelTransaction($model, $userId)
    {
          
        $electricityTransactions = $model::select('transaction_id', 'status', 'amount', 'created_at')
        ->where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->paginate(2);

        return $electricityTransactions;
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