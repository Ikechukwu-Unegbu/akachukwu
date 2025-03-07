<?php

namespace App\Livewire\Admin\Transaction\Airtime;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Services\CalculateDiscount;
use App\Models\Utility\AirtimeTransaction;

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
        $this->authorize('view airtime transaction');
    }

    public function performReimbursement()
    {
        if (!count(array_keys(array_filter($this->transactions)))) {
            return $this->dispatch('error-toastr', ['message' => "No transactions selected. Please choose at least one transaction to proceed with the reimbursement."]);
        }

        return DB::transaction(function () {
            foreach (array_keys(array_filter($this->transactions)) as $key => $value) {
                $transaction = AirtimeTransaction::where('id', $value)->first();    
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
    }

    private function refunded($transaction, $user, $amount) : void
    {
        $user->account_balance += $amount;
        $user->save();
        $transaction->refund();
    }

    public function render()
    {
        return view('livewire.admin.transaction.airtime.index', [
            'airtime_transactions' =>  AirtimeTransaction::with(['user', 'network'])->search($this->search)->latest('created_at')->paginate($this->perPage)
        ]);
    }
}
