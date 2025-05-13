<?php

namespace App\Livewire\Admin\Transfer;

use App\Models\MoneyTransfer;
use App\Services\Payment\Transfer\VastelMoneyTransfer;
use Livewire\Component;

class InAppDetails extends Component
{
    public $transfer;
    public $action = '';
    protected $vastelMoneyTransfer;

    public $adminNotes;

    public function __construct()
    {
        $this->vastelMoneyTransfer = app(VastelMoneyTransfer::class);
    }

    public function mount(MoneyTransfer $transfer)
    {
        $this->transfer = $transfer;
        // $this->adminNotes = $this->transfer->note;
    }

    public function updateTransfer($action)
    {
        $this->validate([
            'adminNotes' => 'nullable|string',
        ]);

        $transfer = $this->transfer;

        if ($action === 'retry') {
            try {

                $handleMoneyTransfer = $this->vastelMoneyTransfer->retry($this->transfer);

                if ($handleMoneyTransfer?->status) {
                    $this->vastelTransactionStatus = true;
                    $this->transfer->update([
                        'transfer_status' => 'successful',
                        'note' => $this->adminNotes,
                    ]);

                    $message = "Retry Transaction";
                    $adminNote = $this->adminNotes ?? null;
                    $this->addTransferLog($this->transfer, $message, $adminNote);

                    $this->dispatch('success-toastr', ['message' => $handleMoneyTransfer?->message]);
                    session()->flash('success', $handleMoneyTransfer?->message);
                    $this->redirect(url()->previous());
                    return;
                }

                $this->vastelTransactionStatus = false;
                $this->dispatch('error-toastr', ['message' => $handleMoneyTransfer?->message ?? 'Transfer failed']);
                return;

            } catch (\Exception $e) {
                $this->vastelTransactionStatus = false;
                $this->dispatch('error-toastr', ['message' => $e->getMessage() ?? 'An error occurred during transfer']);
                return;
            }
        } elseif ($action === 'reverse') {

            try {

                $handleMoneyTransfer = $this->vastelMoneyTransfer->reverse($this->transfer);

                if ($handleMoneyTransfer?->status) {
                    $this->vastelTransactionStatus = true;

                    $this->transfer->update([
                        'transfer_status' => 'refunded',
                        'note' => $this->adminNotes,
                    ]);

                    $message = "Reverse Transaction";
                    $adminNote = $this->adminNotes ?? null;
                    $this->addTransferLog($this->transfer, $message, $adminNote);

                    $this->dispatch('success-toastr', ['message' => $handleMoneyTransfer?->message]);
                    session()->flash('success', $handleMoneyTransfer?->message);
                    $this->redirect(url()->previous());
                    return;
                }

                $this->vastelTransactionStatus = false;
                $this->dispatch('error-toastr', ['message' => $handleMoneyTransfer?->message ?? 'Transfer failed']);
                return;

            } catch (\Exception $e) {
                $this->vastelTransactionStatus = false;
                $this->dispatch('error-toastr', ['message' => $e->getMessage() ?? 'An error occurred during transfer']);
                return;
            }

        } elseif ($action === 'flag') {
            $transfer->update([
                'note' => '[FLAGGED] ' . $this->adminNotes,
                'transfer_status' => 'failed',
            ]);

            $message = "Flagged Transaction";
            $adminNote = $this->adminNotes ?? null;
            $this->addTransferLog($this->transfer, $message, $adminNote);

            $this->dispatch('success-toastr', ['message' => 'Transaction Successfully Flagged']);
            session()->flash('success', 'Transaction Successfully Flagged');
            $this->redirect(url()->previous());
            return;
        }

        $this->reset(['action', 'selectedTransfer']);
        session()->flash('message', 'Transfer action completed successfully.');
    }

    public function addTransferLog($transfer, string $message, ?string $adminNote = null): void
    {
        $existingLogs = $transfer->logs;
        $log = is_string($existingLogs) ? json_decode($existingLogs, true) ?? [] : (array) $existingLogs ?? [];

        $notePart = $adminNote !== null ? ", Note: " . $adminNote : ', Note: N/A';
        $newLog = auth()->user()->name . " " . $message . " (Timestamp: " . date('d m Y H:i:sA') . ")" . $notePart;

        array_unshift($log, $newLog);

        $logsToSave = is_string($existingLogs) ? json_encode($log) : $log;
        $transfer->update(['logs' => $logsToSave]);
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'statusFilter',
            'dateFrom',
            'dateTo',
            'amountFrom',
            'amountTo',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.transfer.in-app-details');
    }
}
