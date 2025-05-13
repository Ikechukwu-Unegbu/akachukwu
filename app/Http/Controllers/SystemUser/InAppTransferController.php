<?php

namespace App\Http\Controllers\SystemUser;

use Illuminate\Http\Request;
use App\Models\MoneyTransfer;
use App\Http\Controllers\Controller;
use App\Services\Payment\Transfer\VastelMoneyTransfer;

class InAppTransferController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 50);
        $perPages = [50, 100, 200];

        $query = MoneyTransfer::query()
            ->with(['sender', 'receiver'])
            ->isInternal()
            ->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        if ($request->has('statusFilter') && $request->statusFilter) {
            $query->where('transfer_status', $request->statusFilter);
        }

        if ($request->has('dateFrom') && $request->dateFrom) {
            $query->whereDate('created_at', '>=', $request->dateFrom);
        }

        if ($request->has('dateTo') && $request->dateTo) {
            $query->whereDate('created_at', '<=', $request->dateTo);
        }

        if ($request->has('amountFrom') && $request->amountFrom) {
            $query->where('amount', '>=', $request->amountFrom);
        }

        if ($request->has('amountTo') && $request->amountTo) {
            $query->where('amount', '<=', $request->amountTo);
        }

        $transfers = $query->paginate($perPage);

        return view('system-user.transfer.in-app.index', [
            'transfers' => $transfers,
            'perPages' => $perPages,
            'filters' => $request->only([
                'search',
                'statusFilter',
                'dateFrom',
                'dateTo',
                'amountFrom',
                'amountTo',
                'perPage'
            ])
        ]);
    }

    public function show(MoneyTransfer $transfer)
    {
        return view('system-user.transfer.in-app.show', [
            'transfer'  => $transfer->load(['sender', 'receiver'])
        ]);
    }

    public function resetFilters()
    {
        return redirect()->route('admin.transfer.in-app');
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

                $this->addTransferLog($transfer, "Retried Transaction", $adminNotes);

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

            $result = (new VastelMoneyTransfer)->reverse($transfer);

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
            // 'note' => ($adminNotes) ? '[FLAGGED] ' . $adminNotes : '',
            'transfer_status' => 'failed',
        ]);

        $this->addTransferLog($transfer, "Flagged Transaction", $adminNotes);

        return back()->with('success', 'Transaction Successfully Flagged');
    }

    protected function addTransferLog(MoneyTransfer $transfer, string $message, ?string $adminNote = null): void
    {
        $existingLogs = $transfer->logs;
        $log = is_string($existingLogs) ? json_decode($existingLogs, true) ?? [] : (array) $existingLogs ?? [];

        $notePart = $adminNote !== null ? PHP_EOL .  ", Note: " . $adminNote : PHP_EOL .  ', Note: N/A';
        $newLog = auth()->user()->name . " " . $message . " (Timestamp: " . date('d m Y h:i:sA') . ")" . $notePart;

        array_unshift($log, $newLog);

        $logsToSave = is_string($existingLogs) ? json_encode($log) : $log;
        $transfer->update(['logs' => $logsToSave]);
    }
}
