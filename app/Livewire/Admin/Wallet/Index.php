<?php

namespace App\Livewire\Admin\Wallet;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class Index extends Component
{
    use WithPagination;

    public $perPage = 20;
    public $perPages = [20, 50];
    public $search;
    public $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function exportPdf()
    {
        // Get all records for PDF export (not paginated)
        $walletHistories = $this->user->checkUserTransactionHistories('*', $this->user->id);
        $filterSummary = $this->getFilterSummary();

        $pdf = Pdf::loadView('livewire.admin.wallet.pdf', [
            'walletHistories' => $walletHistories,
            'user' => $this->user,
            'filterSummary' => $filterSummary
        ]);

        $filename = 'wallet-history-' . $this->user->username . '-' . date('Y-m-d-H-i-s') . '.pdf';
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    private function getFilterSummary()
    {
        $summary = [];

        if ($this->search) {
            $summary[] = "Search: {$this->search}";
        }

        return $summary;
    }

    public function render()
    {
        return view('livewire.admin.wallet.index', [
            'walletHistories' => $this->user->checkUserTransactionHistories($this->perPage, $this->user->id)
        ]);
    }
}
