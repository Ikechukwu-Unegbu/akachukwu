<?php

namespace App\Http\Controllers\SystemUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment\CryptoTransactionsLog;

class CryptoWalletFundingController extends Controller
{
    
    public function index(Request $request)
{
    $query = \App\Models\Payment\CryptoTransactionsLog::with('user')->orderBy('id', 'desc');

    // Apply filters
    if ($search = $request->input('search')) {
        $query->whereHas('user', function ($q) use ($search) {
            $q->where('email', 'like', "%{$search}%")
              ->orWhere('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->input('status'));
    }

    if ($request->filled('dateFrom')) {
        $query->whereDate('created_at', '>=', $request->input('dateFrom'));
    }

    if ($request->filled('dateTo')) {
        $query->whereDate('created_at', '<=', $request->input('dateTo'));
    }

    if ($request->filled('amountFrom')) {
        $query->where('amount', '>=', $request->input('amountFrom'));
    }

    if ($request->filled('amountTo')) {
        $query->where('amount', '<=', $request->input('amountTo'));
    }

    $transactions = $query->paginate($request->get('perPage', 50));

    // define statuses for dropdown
    $statuses = ['success', 'failed', 'pending', 'processing', 'refunded'];

    // collect all request filters
    $filters = $request->all();

    return view('system-user.transfer.crypto-funding.crypto-index', compact('transactions', 'statuses', 'filters'));
}

}
