<?php

namespace App\Http\Controllers\SystemUser;

use App\Models\ScheduledTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Data\DataTransaction;
use App\Models\Utility\CableTransaction;
use App\Models\Utility\AirtimeTransaction;
use App\Models\Utility\ElectricityTransaction;

class ScheduledTransactionController extends Controller
{
    protected $transactionModels = [
        'data' => DataTransaction::class,
        'airtime' => AirtimeTransaction::class,
        'cable' => CableTransaction::class,
        'electricity' => ElectricityTransaction::class,
    ];

    public function index(Request $request)
    {
        $query = ScheduledTransaction::query();

        if ($request->filled('product_type')) {
            $query->where('type', $request->product_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', Carbon::parse($request->date_from));
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', Carbon::parse($request->date_to));
        }

        $transactions =  $query->latest()->paginate(50);

        $productTypes = ['airtime', 'data'];
        $statuses = ['pending', 'processing', 'completed', 'failed', 'disabled'];

        return view('system-user.scheduled-transactions.index', compact(
            'transactions',
            'productTypes',
            'statuses'
        ));
    }

    protected function buildQuery(Request $request)
    {
        $query = null;

        if ($request->filled('product_type') && isset($this->transactionModels[$request->product_type])) {
            $typesToQuery = [$request->product_type];
        } else {
            $typesToQuery = array_keys($this->transactionModels);
        }

        foreach ($typesToQuery as $type) {
            $model = $this->transactionModels[$type];

            $modelQuery = $model::query()
                ->select([
                    DB::raw("'$type' as product_type"),
                    'id',
                    'transaction_id',
                    'user_id',
                    'amount',
                    'balance_before',
                    'balance_after',
                    'status',
                    'vendor_status',
                    'created_at',
                    'updated_at'
                ]);

            if ($request->filled('status')) {
                $modelQuery->where('vendor_status', $request->status);
            }

            if ($request->filled('date_from')) {
                $modelQuery->where('created_at', '>=', Carbon::parse($request->date_from));
            }

            if ($request->filled('date_to')) {
                $modelQuery->where('created_at', '<=', Carbon::parse($request->date_to));
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $modelQuery->where(function ($q) use ($search, $type) {
                    $q->where('transaction_id', 'like', "%$search%")
                        ->when(in_array($type, ['airtime', 'data']), function ($q) use ($search) {
                            $q->orWhere('mobile_number', 'like', "%$search%");
                        })
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('name', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%")
                                ->orWhere('username', 'like', "%$search%");
                        });
                });
            }

            $query = $query ? $query->unionAll($modelQuery) : $modelQuery;
        }

        return $query;
    }


    public function show(ScheduledTransaction $transaction)
    {
        return view('system-user.scheduled-transactions.show', [
            'transaction' => $transaction
        ]);
    }

    public function retry($type, $id)
    {
        if (!array_key_exists($type, $this->transactionModels)) {
            abort(404);
        }

        $transaction = $this->transactionModels[$type]::findOrFail($id);

        try {
            // Your retry logic here
            $result = $this->processRetry($transaction);

            if ($result['success']) {
                $transaction->update([
                    'status' => 1,
                    'vendor_status' => 'successful'
                ]);
                return back()->with('success', 'Transaction retried successfully');
            }

            return back()->with('error', 'Retry failed: ' . $result['message']);
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

}
