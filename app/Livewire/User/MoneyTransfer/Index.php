<?php

namespace App\Livewire\User\MoneyTransfer;

use App\Models\Bank;
use App\Services\Payment\MonnifyService;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Index extends Component
{
    public $recipient_account;
    public $amount;
    public $remark;
    public $bank;

    public function submit()
    {
        $this->validate([
            'recipient_account' =>  'required|numeric|min:10',
            'bank'              =>  'required',
            'remark'            =>  'nullable|max:30',
            'amount'            =>  'required|numeric'
        ]);

        // $bank = Bank::find($this->bank);
        // $monnify = MonnifyService::moneyTransfer($this->amount, $bank->code, $bank->name, $this->recipient_account, $this->remark);

        // dd($monnify);
    }


    public function render()
    {
        return view('livewire.user.money-transfer.index', [
            // 'banks' =>  Bank::get()
        ])->layout('layouts.new-guest');
    }
}
