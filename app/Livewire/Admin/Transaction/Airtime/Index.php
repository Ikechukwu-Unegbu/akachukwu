<?php

namespace App\Livewire\Admin\Transaction\Airtime;

use Livewire\Component;
use Livewire\WithPagination;
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

        if ($this->action === 'debit') {

            foreach (array_keys(array_filter($this->transactions)) as $key => $value) {
                $transaction = AirtimeTransaction::where('id', $value)->first();    
                $amount = $transaction->amount;
                $transaction->user->setTransaction($amount);
                $transaction->debit();
            }

            $this->dispatch('success-toastr', ['message' => 'Transaction Debited Successfully']);
            session()->flash('success', 'Transaction Debited Successfully');
            $this->redirect(url()->previous());
        }

        if ($this->action === 'refund') {

            foreach (array_keys(array_filter($this->transactions)) as $key => $value) {
                $transaction = AirtimeTransaction::where('id', $value)->first();    
                $amount = $transaction->amount;
                $transaction->user->setAccountBalance($amount);
                $transaction->refund();
            }

            $this->dispatch('success-toastr', ['message' => 'Transaction Refunded Successfully']);
            session()->flash('success', 'Transaction Refunded Successfully');
            $this->redirect(url()->previous());
        }       
        
    }

    public function render()
    {
        return view('livewire.admin.transaction.airtime.index', [
            'airtime_transactions' =>  AirtimeTransaction::with(['user', 'network'])->search($this->search)->latest('created_at')->paginate($this->perPage)
        ]);
    }
}
