<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\V1\Education\ResultCheckerController;
use App\Models\Data\DataTransaction;
use App\Models\Education\ResultChecker;
use App\Models\Education\ResultCheckerTransaction;
use App\Models\Utility\AirtimeTransaction;
use App\Models\Utility\CableTransaction;
use App\Models\Utility\ElectricityTransaction;
use Illuminate\Http\Request;

class ApiResponseController extends Controller
{
 
    public function __invoke($type, $transactionId)
    {
        $transaction = match ($type) {
            'data' => DataTransaction::where('transaction_id', $transactionId)->first(),
            'electricity' => ElectricityTransaction::where('transaction_id', $transactionId)->first(),
            'cable' => CableTransaction::where('transaction_id', $transactionId)->first(),
            'airtime' => AirtimeTransaction::where('transaction_id', $transactionId)->first(),
            'result'=> ResultCheckerTransaction::where('transaction_id', $transactionId)->first(),
            default => null
        };

        if (!$transaction) {
            return response()->json(['error' => $transaction ? 'Transaction not found' : 'Invalid transaction type'], $transaction ? 404 : 400);
        }
        $data = json_decode($transaction->api_response, true);
        // dd($transaction);

        return view('pages.transaction.api-response')->with('data', $transaction);
    }
}
