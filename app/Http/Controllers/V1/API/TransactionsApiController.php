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

        $category = request()->input('category');
        $endDate = request()->input('endDate');
        $startDate = request()->input('startDate');
        $perPage= request()->input('perpage');

        $result = TransactionApiService::fetchTransactions($category, $startDate, $endDate, $perPage);
        return response()->json($result);
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
        } elseif (isset($transaction->status) && !$transaction->status) {
            return $transaction;
        }

        return ApiHelper::sendResponse($transaction, 'Transaction fetched');
    }
}
