<?php

namespace App\Http\Controllers\SystemUser;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Mail\TransactionMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Data\DataTransaction;
use App\Models\ScheduledTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Utility\CableTransaction;
use App\Exports\ScheduledTransactionsPdf;
use App\Models\Utility\AirtimeTransaction;
use App\Exports\ScheduledTransactionsExport;
use App\Jobs\ProcessScheduledTransactionJob;
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

        if ($request->filled('product_type')) $query->where('type', $request->product_type);

        if ($request->filled('frequency')) $query->where('frequency', $request->frequency);

        if ($request->filled('status')) $query->where('status', $request->status);

        if ($request->filled('date_from')) $query->where('created_at', '>=', Carbon::parse($request->date_from));

        if ($request->filled('date_to')) $query->where('created_at', '<=', Carbon::parse($request->date_to));

        $transactions = $query->latest()->paginate(50);

        $productTypes = ['airtime', 'data'];
        $statuses = ['pending', 'processing', 'completed', 'failed', 'disabled'];
        $frequencies = ['hourly', 'daily', 'weekly', 'monthly', 'yearly'];

        if ($request->has('export')) {
            if ($request->export == 'excel') {
                return Excel::download(new ScheduledTransactionsExport, 'scheduled-transactions.xlsx');
            } elseif ($request->export == 'pdf') {
                return Excel::download(new ScheduledTransactionsPdf, 'scheduled-transactions.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
            }
        }


        return view('system-user.scheduled-transactions.index', compact(
            'transactions',
            'productTypes',
            'statuses',
            'frequencies'
        ));
    }

    public function show(ScheduledTransaction $transaction)
    {
        $latestTransaction = null;

        if ($transaction->type === 'airtime') {
            $latestTransactions = AirtimeTransaction::where('scheduled_transaction_id', $transaction->id)
                ->latest()
                ->paginate(50);
        } elseif ($transaction->type === 'data') {
            $latestTransactions = DataTransaction::where('scheduled_transaction_id', $transaction->id)
                ->latest()
                ->paginate(50);
        }

        return view('system-user.scheduled-transactions.show', [
            'transaction' => $transaction,
            'latestTransactions' => $latestTransactions
        ]);
    }

    public function update(Request $request, ScheduledTransaction $transaction)
    {
        $action = $request->input('action');
        $noteContent = $request->input('note');

        try {
            $notes = $transaction->notes ?? [];

            switch ($action) {
                case 'retry':
                    $adminId = auth()->id();
                    $transaction->update(['next_run_at' => now()]);

                    dispatch(new ProcessScheduledTransactionJob($transaction));

                    Auth::loginUsingId($adminId);

                    $notes[] = [
                        'type' => 'retry',
                        'content' => 'Transaction retry requested by admin',
                        'admin_id' => auth()->id(),
                        'timestamp' => now(),
                    ];

                    return response()->json(['success' => 'Transaction queued for retry']);

                case 'cancel':

                    $transaction->update([
                        'status' => 'disabled',
                        'next_run_at' => null,
                    ]);


                    $notes[] = [
                        'type' => 'cancel',
                        'content' => $noteContent ?? 'Transaction cancelled by admin',
                        'admin_id' => auth()->id(),
                        'timestamp' => now(),
                    ];

                    return response()->json(['success' => 'Transaction cancelled successfully']);

                case 'notify':

                    $user = $transaction->user;

                    try {
                        Mail::to($user->email)->send(new TransactionMail($transaction));
                    } catch (\Throwable $th) {
                        Log::error($th->getMessage());
                    }

                    $notes[] = [
                        'type' => 'notification',
                        'content' => $noteContent ?? 'User notified about transaction',
                        'admin_id' => auth()->id(),
                        'timestamp' => now(),
                    ];

                    return response()->json(['success' => 'User notified successfully']);

                case 'note':
                    $notes[] = [
                        'type' => 'note',
                        'content' => $noteContent,
                        'admin_id' => auth()->id(),
                        'timestamp' => now(),
                    ];

                    $transaction->update(['notes' => $notes]);

                    return response()->json(['success' => 'Note added successfully']);

                default:
                    return response()->json(['error' => 'Invalid action'], 400);
            }

        } catch (\Exception $e) {
            Log::error("Failed to update transaction {$transaction->id}: " . $e->getMessage());
            return response()->json(['error' => 'Operation failed'], 500);
        }
    }
}
