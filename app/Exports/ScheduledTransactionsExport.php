<?php

namespace App\Exports;

use App\Models\ScheduledTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ScheduledTransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return ScheduledTransaction::with('user')
            ->whereIn('type', ['airtime', 'data'])
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'User',
            'Type',
            'Frequency',
            'Amount',
            'Status',
            'Next Run',
            'Last Run',
            'Created At'
        ];
    }

    public function map($transaction): array
    {
        $amount = json_decode($transaction->payload)->amount;

        return [
            $transaction->id,
            $transaction->user->name,
            ucfirst($transaction->type),
            ucfirst($transaction->frequency),
            config('app.currency') . number_format($amount, 2),
            ucfirst($transaction->status),
            $transaction->next_run_at?->format('Y-m-d H:i'),
            $transaction->last_run_at?->format('Y-m-d H:i'),
            $transaction->created_at->format('Y-m-d H:i')
        ];
    }
}
