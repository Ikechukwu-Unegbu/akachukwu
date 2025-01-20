<?php

namespace App\Livewire\Admin\Transaction\Data;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\CalculateDiscount;
use App\Models\Data\DataTransaction;
use App\Services\Referrals\ReferralService;

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
        $this->authorize('view data transaction');
    }

    public function performReimbursement()
    {
        if (!count(array_keys(array_filter($this->transactions)))) {
            return $this->dispatch('error-toastr', ['message' => "No transactions selected. Please choose at least one transaction to proceed with the reimbursement."]);
        }

        if ($this->action === 'debit') {

            foreach (array_keys(array_filter($this->transactions)) as $key => $value) {
                $transaction = DataTransaction::where('id', $value)->first();    
                $amount = CalculateDiscount::calculate($transaction->amount, $transaction->discount);
                $transaction->user->setTransaction($amount);
                $transaction->debit();
            }

            $this->dispatch('success-toastr', ['message' => 'Transaction Debited Successfully']);
            session()->flash('success', 'Transaction Debited Successfully');
            $this->redirect(url()->previous());
        }

        if ($this->action === 'refund') {

            foreach (array_keys(array_filter($this->transactions)) as $key => $value) {
                $transaction = DataTransaction::where('id', $value)->first();    
                $amount = CalculateDiscount::calculate($transaction->amount, $transaction->discount);
                $transaction->user->setAccountBalance($amount);
                $transaction->refund();
                (new ReferralService)->reverseRferrerpay($transaction);
            }

            $this->dispatch('success-toastr', ['message' => 'Transaction Refunded Successfully']);
            session()->flash('success', 'Transaction Refunded Successfully');
            $this->redirect(url()->previous());
        }       
        
    }

    public function render()
    {
        return view('livewire.admin.transaction.data.index', [
            'data_transactions' =>  DataTransaction::with(['user', 'data_plan'])->search($this->search)->latest('created_at')->paginate($this->perPage)
        ]);
    }
}
