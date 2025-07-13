<?php

namespace App\Http\Controllers\SystemUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletFundingController extends Controller
{
    protected $statuses = [
        'successful',
        'processing',
        'pending',
        'failed',
        'refunded',
        'negative'
    ];

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 50);
        $filters = $request->only([
            'status',
            'dateFrom',
            'dateTo',
            'amountFrom',
            'amountTo',
            'vendor',
            'perPage',
            'search'
        ]);

        $query = DB::table(DB::raw('(
            SELECT id, reference_id as transaction_id, balance_before, balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "wallet funding" as utility, "fa-exchange-alt" as icon, "wallet funding" as title, created_at, "flutterwave" as vendor FROM flutterwave_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, balance_before, balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "wallet funding" as utility, "fa-exchange-alt" as icon, "wallet funding" as title, created_at, "paystack" as vendor FROM paystack_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, balance_before, balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "wallet funding" as utility, "fa-exchange-alt" as icon, "wallet funding" as title, created_at, "payvessel" as vendor FROM pay_vessel_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, balance_before, balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "wallet funding" as utility, "fa-exchange-alt" as icon, "wallet funding" as title, created_at, "monnify" as vendor FROM monnify_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, balance_before, balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "wallet funding" as utility, "fa-exchange-alt" as icon, "wallet funding" as title, created_at, "vastel" as vendor FROM vastel_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, balance_before, balance_after, user_id, amount, status, api_status as vendor_status, "bank" as subscribed_to, reference_id as plan_name, "funding" as type, "wallet funding" as utility, "fa-exchange-alt" as icon, "wallet funding" as title, created_at, "palmpay" as vendor FROM palm_pay_transactions
        ) as transactions'))
            ->leftJoin('users', 'transactions.user_id', '=', 'users.id')
            ->select('transactions.*', 'users.username as username', 'users.email as email', 'users.name as user_name');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transactions.transaction_id', 'LIKE', "%{$search}%")
                  ->orWhere('users.username', 'LIKE', "%{$search}%")
                  ->orWhere('users.email', 'LIKE', "%{$search}%")
                  ->orWhere('users.name', 'LIKE', "%{$search}%")
                  ->orWhere('transactions.amount', 'LIKE', "%{$search}%")
                  ->orWhere('transactions.vendor', 'LIKE', "%{$search}%");
            });
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('dateFrom')) {
            $query->whereDate('created_at', '>=', $request->dateFrom);
        }
        if ($request->filled('dateTo')) {
            $query->whereDate('created_at', '<=', $request->dateTo);
        }
        if ($request->filled('amountFrom')) {
            $query->where('amount', '>=', $request->amountFrom);
        }
        if ($request->filled('amountTo')) {
            $query->where('amount', '<=', $request->amountTo);
        }
        if ($request->filled('vendor')) {
            $query->where('vendor', $request->vendor);
        }

        $query->orderBy('created_at', 'desc');
        $transactions = $query->paginate($perPage);

        return view('system-user.transfer.wallet-funding.index', [
            'transactions' => $transactions,
            'statuses' => $this->statuses,
            'filters' => $filters
        ]);
    }

    public function show($transaction_id)
    {
        $transaction = DB::table(DB::raw('(
            SELECT id, reference_id as transaction_id, balance_before, balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "wallet funding" as utility, "fa-exchange-alt" as icon, "wallet funding" as title, created_at, "flutterwave" as vendor FROM flutterwave_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, balance_before, balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "wallet funding" as utility, "fa-exchange-alt" as icon, "wallet funding" as title, created_at, "paystack" as vendor FROM paystack_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, balance_before, balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "wallet funding" as utility, "fa-exchange-alt" as icon, "wallet funding" as title, created_at, "payvessel" as vendor FROM pay_vessel_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, balance_before, balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "wallet funding" as utility, "fa-exchange-alt" as icon, "wallet funding" as title, created_at, "monnify" as vendor FROM monnify_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, balance_before, balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "wallet funding" as utility, "fa-exchange-alt" as icon, "wallet funding" as title, created_at, "vastel" as vendor FROM vastel_transactions
            UNION ALL
            SELECT id, reference_id as transaction_id, balance_before, balance_after, user_id, amount, status, api_status as vendor_status, "bank" as subscribed_to, reference_id as plan_name, "funding" as type, "wallet funding" as utility, "fa-exchange-alt" as icon, "wallet funding" as title, created_at, "palmpay" as vendor FROM palm_pay_transactions
        ) as transactions'))
            ->leftJoin('users', 'transactions.user_id', '=', 'users.id')
            ->select('transactions.*', 'users.username as username', 'users.email as email', 'users.name as user_name')
            ->where('transactions.transaction_id', $transaction_id)
            ->first();

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        return view('system-user.transfer.wallet-funding.show', [
            'transaction' => $transaction
        ]);
    }
}
