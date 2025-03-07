<?php

namespace App\Livewire\Admin\Transaction\MoneyTransfer;

use App\Models\MoneyTransfer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

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
        // $this->authorize('view money transaction');
    }

    public function performReimbursement()
    {
        if (!count(array_keys(array_filter($this->transactions)))) {
            return $this->dispatch('error-toastr', ['message' => "No transactions selected. Please choose at least one transaction to proceed with the reimbursement."]);
        }

        return DB::transaction(function () {
            foreach (array_keys(array_filter($this->transactions)) as $key => $value) {
                $transaction = MoneyTransfer::findOrFail($value);

                $amount = $transaction->amount+$transaction->charges;

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
        $transaction->update([
            'status'            =>  0,
            'transfer_status'   =>  'failed'
        ]);
    }

    private function refunded($transaction, $user, $amount) : void
    {
        $user->account_balance += $amount;
        $user->save();
        $transaction->update([
            'status'            =>  2,
            'transfer_status'   =>  'refunded',
            'balance_after_refund' => $amount,
        ]);
    }

    public function render()
    {
        return view('livewire.admin.transaction.money-transfer.index', [
            'money_transactions' =>  MoneyTransfer::with(['sender', 'receiver'])->search($this->search)->latest('created_at')->paginate($this->perPage)
        ]);
    }
}
