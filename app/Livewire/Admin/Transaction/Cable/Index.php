<?php

namespace App\Livewire\Admin\Transaction\Cable;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Services\CalculateDiscount;
use Illuminate\Support\Facades\Log;
use App\Models\Utility\CableTransaction;
use App\Notifications\CreditNofitication;
use App\Notifications\FundDeductionNotification;

class Index extends Component
{
    use WithPagination;

    public $perPage = 50;
    public $perPages = [50, 100, 200];
    public $search;

    public $action = "debit";
    public $transactions = [];

    public function mount()
    {
        $this->authorize('view cable transaction');
    }

    public function performReimbursement()
    {
        if (!count(array_keys(array_filter($this->transactions)))) {
            return $this->dispatch('error-toastr', ['message' => "No transactions selected. Please choose at least one transaction to proceed with the reimbursement."]);
        }

        return DB::transaction(function () {
            foreach (array_keys(array_filter($this->transactions)) as $key => $value) {
                $transaction = CableTransaction::where('id', $value)->first();

                if ($transaction->vendor_status === 'refunded') {
                    return $this->dispatch('error-toastr', ['message' => "Transaction {$transaction->id} has not been refunded."]);
                }

                $amount = CalculateDiscount::calculate($transaction->amount, $transaction->discount);

                $user = User::where('id', $transaction->user_id)->lockForUpdate()->first();

                if ($this->action === 'debit')
                    $this->debited($transaction, $user, $amount);

                if ($this->action === 'refund')
                    $this->refunded($transaction, $user, $amount);
            }

            $message = ($this->action === 'debit') ? 'Debited' : ($this->action === 'refund' ? 'Refunded' : '');

            $this->dispatch('success-toastr', ['message' => "Transaction {$message} Successfully"]);
            session()->flash('success', "Transaction {$message} Successfully");
            $this->redirect(url()->previous());
        });
    }

    private function debited($transaction, $user, $amount) : void
    {
        $user->account_balance -= $amount;
        $user->save();
        $transaction->debit();

        try {
            $user->notify(new FundDeductionNotification('Cable', $amount, $user->account_balance));
        } catch (\Throwable $th) {
            Log::error('Failed to debit user account notification: ' . $th->getMessage());
        }
    }

    private function refunded($transaction, $user, $amount) : void
    {
        $user->account_balance += $amount;
        $user->save();
        $transaction->refund();

        try {
            $user->notify(new CreditNofitication('Cable', $amount, $user->account_balance));
        } catch (\Throwable $th) {
            Log::error('Failed to refund user account notification: ' . $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.transaction.cable.index', [
            'cable_transactions' =>  CableTransaction::with(['user'])->search($this->search)->latest('created_at')->paginate($this->perPage)
        ]);
    }
}
