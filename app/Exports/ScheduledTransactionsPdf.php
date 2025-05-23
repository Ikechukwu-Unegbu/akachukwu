<?php

namespace App\Exports;

use App\Models\ScheduledTransaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ScheduledTransactionsPdf implements FromView
{
    public function view(): View
    {
        $transactions = ScheduledTransaction::with('user')
            ->whereIn('type', ['airtime', 'data'])
            ->latest()
            ->get();

        return view('exports.scheduled-transactions-pdf', [
            'transactions' => $transactions
        ]);
    }
}
