<?php

namespace App\Livewire\Admin\Transfer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MoneyTransfer;

class InApp extends Component
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
    public $adminNotes = '';


    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'amountFrom' => ['except' => ''],
        'amountTo' => ['except' => ''],
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
            'statusFilter',
            'dateFrom',
            'dateTo',
            'amountFrom',
            'amountTo',
        ]);
    }

    public function render()
    {
        $query = MoneyTransfer::query()
            ->with(['sender', 'receiver'])
            ->search($this->search)
            ->isInternal()
            ->when($this->statusFilter, function ($q) {
                return $q->where('transfer_status', $this->statusFilter);
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
            ->orderBy('created_at', 'desc');

        $transfers = $query->paginate($this->perPage);

        return view('livewire.admin.transfer.in-app', [
            'transfers' => $transfers,
        ]);
    }
}
