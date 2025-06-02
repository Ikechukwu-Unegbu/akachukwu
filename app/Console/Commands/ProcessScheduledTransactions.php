<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Data\DataTransaction;
use App\Models\ScheduledTransaction;
use App\Models\Utility\AirtimeTransaction;
use App\Jobs\ProcessScheduledTransactionJob;

class ProcessScheduledTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-scheduled-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        $transactions = ScheduledTransaction::where('status', '!=', 'disabled')
            ->where('next_run_at', '<=', $now)
            ->get();

        foreach ($transactions as $schedule) {
            dispatch(new ProcessScheduledTransactionJob($schedule));
        }
    }
}
