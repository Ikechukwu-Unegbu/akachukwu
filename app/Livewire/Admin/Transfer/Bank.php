<?php

namespace App\Livewire\Admin\Transfer;

use App\Models\Bank as ModelBank;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MoneyTransfer;

class Bank extends Component
{
    use WithPagination;

    public $perPage = 50;
    public $perPages = [50, 100, 200];
    public $search;

    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $amountFrom = '';
    public $amountTo = '';
    public $selectedTransfer = null;

    public $bank;
    public $status;

    public $statuses = [
        'successful', 'processing', 'pending', 'failed', 'refunded', 'negative'
    ];


    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'amountFrom' => ['except' => ''],
        'amountTo' => ['except' => ''],
        'bank' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function selectTransfer($transferId)
    {
        $this->selectedTransfer = MoneyTransfer::with(['sender', 'receiver'])->find($transferId);
        $this->adminNotes = $this->selectedTransfer->comment ?? '';
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'status',
            'dateFrom',
            'dateTo',
            'amountFrom',
            'amountTo',
            'bank'
        ]);
    }

    public function render()
    {
        $query = MoneyTransfer::query()
            ->with(['sender', 'receiver'])
            ->search($this->search)
            ->isExternal()
            ->when($this->status, function ($q) {
                return $q->where('transfer_status', $this->status);
            })
            ->when($this->dateFrom, function ($q) {
                return $q->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($q) {
                return $q->whereDate('created_at', '<=', $this->dateTo);
            })
            ->when($this->amountFrom, function ($q) {
                return $q->where('amount', '>=', $this->amountFrom);
            })
            ->when($this->amountTo, function ($q) {
                return $q->where('amount', '<=', $this->amountTo);
            })
            ->when($this->bank, function ($q) {
                return $q->where('bank_code',  $this->bank);
            })
            ->orderBy('created_at', 'desc');

        $transfers = $query->paginate($this->perPage);


        return view('livewire.admin.transfer.bank', [
            'transfers' => $transfers,
            'banks'    =>   ModelBank::isPalmPay()->orderBy('name')->get()
        ]);
    }
}
