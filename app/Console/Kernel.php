<?php

namespace App\Console;

use App\Jobs\EnforcePostNoDebitJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('vendor:record-balance')->dailyAt('00:00');
        $schedule->command('vendor:record-balance')->dailyAt('23:59');
        $schedule->command('app:process-scheduled-transactions')->everyMinute();
        $schedule->job(new EnforcePostNoDebitJob)->everyTenMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
