<?php

namespace App\Http\Controllers\V1\API;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use App\Models\Utility\ElectricityTransaction;
use App\Models\Data\DataTransaction;
use App\Models\Utility\CableTransaction;
use App\Models\Utility\AirtimeTransaction;
use App\Models\Education\ResultCheckerTransaction;
use App\Services\TransactionApiService;
use Illuminate\Support\Facades\Auth;

class TransactionsApiController extends Controller
{
    
   
    public function index()
    {

        $userId = 2;//Auth::user()->id;
       
        $electricityTransactions = TransactionApiService::getServiceModelTransaction(new ElectricityTransaction(), $userId);
        $airtimeTransactions = TransactionApiService::getServiceModelTransaction(new AirtimeTransaction(), $userId);
        $dataTransactions = TransactionApiService::getServiceModelTransaction(new DataTransaction(), $userId);
        $resultCheckerTransactions = TransactionApiService::getServiceModelTransaction(new ResultCheckerTransaction(), $userId);
        $cableTransactions= TransactionApiService::getServiceModelTransaction(new CableTransaction(), $userId);

        $collections = [
            $electricityTransactions->getCollection()->map(fn($item) => $item->toArray() + ['model_source' => 'ElectricityTransaction']),
            $airtimeTransactions->getCollection()->map(fn($item) => $item->toArray() + ['model_source' => 'AirtimeTransaction']),
            $dataTransactions->getCollection()->map(fn($item) => $item->toArray() + ['model_source' => 'DataTransaction']),
            $resultCheckerTransactions->getCollection()->map(fn($item) => $item->toArray() + ['model_source' => 'ResultCheckerTransaction']),
            $cableTransactions->getCollection()->map(fn($item) => $item->toArray() + ['model_source' => 'CableTransaction']),
        ];

        $allTransactions = collect($collections)->sortByDesc('created_at');
    
        return ApiHelper::sendResponse($allTransactions, 'all transactions fetched');

    }

    public function show($id)
    {
        $type = request()->input('type');
        if ($type === null) {
            return ApiHelper::sendError(['unknown transaction type'], 'unknown transaction type');
        }

        $transaction = TransactionApiService::getSingleTransaction($type, $id);
        if (!$transaction) {
            return ApiHelper::sendError(['transaction not found'], 'transaction not found');
        }

        return ApiHelper::sendResponse($transaction, '');
    }

}
