<?php

namespace App\Http\Controllers\SystemUser;

use Log;
use App\Models\Bank;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\MoneyTransfer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Payment\Transfer\VastelMoneyTransfer;

class BankTransferController extends Controller
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
        $perPages = [50, 100, 200];

        $query = MoneyTransfer::query()
            ->with(['sender', 'receiver'])
            ->isExternal()
            ->orderBy('created_at', 'desc');

        // Apply search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('transfer_status', $request->status);
        }

        // Apply date filters
        if ($request->filled('dateFrom')) {
            $query->whereDate('created_at', '>=', $request->dateFrom);
        }

        if ($request->filled('dateTo')) {
            $query->whereDate('created_at', '<=', $request->dateTo);
        }

        // Apply amount filters
        if ($request->filled('amountFrom')) {
            $query->where('amount', '>=', $request->amountFrom);
        }

        if ($request->filled('amountTo')) {
            $query->where('amount', '<=', $request->amountTo);
        }

        // Apply bank filter
        if ($request->filled('bank')) {
            $query->where('bank_code', $request->bank);
        }

        $transfers = $query->paginate($perPage);
        $banks = Bank::isPalmPay()->orderBy('name')->get();

        return view('system-user.transfer.bank.index', [
            'transfers' => $transfers,
            'banks' => $banks,
            'statuses' => $this->statuses,
            'filters' => $request->only([
                'search',
                'status',
                'dateFrom',
                'dateTo',
                'amountFrom',
                'amountTo',
                'bank',
                'perPage'
            ])
        ]);
    }

    public function show(MoneyTransfer $transfer)
    {
        return view('system-user.transfer.bank.show', [
            'transfer' => $transfer->load(['sender']),
            'apiResponse' => json_decode($transfer->api_response, true)
        ]);
    }

    public function update(Request $request, MoneyTransfer $transfer)
    {
        $request->validate([
            'adminNotes' => 'nullable|string',
            'action' => 'required|in:retry,reverse,flag'
        ]);

        $action = $request->action;
        $adminNotes = $request->adminNotes;

        if ($action === 'retry') {
            return $this->handleRetry($transfer, $adminNotes);
        } elseif ($action === 'reverse') {
            return $this->handleReverse($transfer, $adminNotes);
        } elseif ($action === 'flag') {
            return $this->handleFlag($transfer, $adminNotes);
        }

        return back()->with('error', 'Invalid action');
    }

    protected function handleRetry(MoneyTransfer $transfer, $adminNotes)
    {
        try {
            $result = (new VastelMoneyTransfer)->retry($transfer);

            if ($result?->status) {
                $transfer->update([
                    'transfer_status' => 'successful',
                    'note' => $adminNotes,
                ]);

                $this->addTransferLog($transfer, "Retry Transaction", $adminNotes);

                return back()->with('success', $result?->message);
            }

            return back()->with('error', $result?->message ?? 'Transfer failed');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage() ?? 'An error occurred during transfer');
        }
    }

    protected function handleReverse(MoneyTransfer $transfer, $adminNotes)
    {
        try {
            $result = (new VastelMoneyTransfer)->reverseBankTransfer($transfer);

            if ($result?->status) {
                $transfer->update([
                    'transfer_status' => 'refunded',
                    'note' => $adminNotes,
                ]);

                $this->addTransferLog($transfer, "Reversed Transaction", $adminNotes);

                return back()->with('success', $result?->message);
            }

            return back()->with('error', $result?->message ?? 'Transfer failed');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage() ?? 'An error occurred during transfer');
        }
    }

    protected function handleFlag(MoneyTransfer $transfer, $adminNotes)
    {
        $transfer->update([
            // 'note' => '[FLAGGED] ' . $adminNotes,
            'transfer_status' => 'failed',
        ]);

        $this->addTransferLog($transfer, "Flagged Transaction", $adminNotes);

        return back()->with('success', 'Transaction Successfully Flagged');
    }

    protected function addTransferLog(MoneyTransfer $transfer, string $message, ?string $adminNote = null): void
    {
        $existingLogs = $transfer->logs;
        $log = is_string($existingLogs) ? json_decode($existingLogs, true) ?? [] : (array) $existingLogs ?? [];

        $notePart = $adminNote !== null ? ", Note: " . $adminNote : ', Note: N/A';
        $newLog = auth()->user()->name . " " . $message . " (Timestamp: " . date('d m Y H:i:sA') . ")" . $notePart;

        array_unshift($log, $newLog);

        $logsToSave = is_string($existingLogs) ? json_encode($log) : $log;
        $transfer->update(['logs' => $logsToSave]);
    }

    public function performReimbursement(Request $request)
    {
        $request->validate([
            'action' => 'required|in:debit,refund',
            'transactions' => 'required|array',
            'transactions.*' => 'exists:money_transfers,id'
        ]);

        try {
                DB::beginTransaction();
                foreach ($request->transactions as $key => $value) {
                    $transaction = MoneyTransfer::findOrFail($value);

                    $amount = $transaction->amount+$transaction->charges;

                    $user = User::where('id', $transaction->user_id)->lockForUpdate()->first();

                    if ($request->action === 'debit')
                        $this->debited($transaction, $user, $amount);

                    if ($request->action === 'refund')
                        $this->refunded($transaction, $user, $amount);
                }
                DB::commit();
                $message = ($request->action === 'debit') ? 'Debited' : ($request->action === 'refund' ? 'Refunded' : '');

                session()->flash('success', "Transaction {$message} Successfully");

                return response()->json([
                    'status' => true,
                    'message' => "Transaction {$message} Successfully"
                ]);

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            return response()->json([
                'status' => false,
                'message' => "Transaction failed!"
            ]);
        }
    }

    private function debited($transaction, $user, $amount) : void
    {
        $transaction->update(['sender_balance_before' => $user->account_balance]);

        $user->account_balance -= $amount;
        $user->save();
        $transaction->update([
            'status'            =>  0,
            'transfer_status'   =>  'failed',
            'sender_balance_after' => $user->account_balance
        ]);
    }

    private function refunded($transaction, $user, $amount) : void
    {
        $transaction->update(['sender_balance_before' => $user->account_balance]);

        $user->account_balance += $amount;
        $user->save();

        $transaction->update([
            'status'            =>  2,
            'transfer_status'   =>  'refunded',
            'balance_after_refund' => $amount,
            'sender_balance_after' => $user->account_balance
        ]);
    }
}
