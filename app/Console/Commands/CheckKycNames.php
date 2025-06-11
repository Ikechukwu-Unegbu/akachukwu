<?php

namespace App\Console\Commands;

use App\Models\User;
use Auth;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\Payment\MonnifyService;

class CheckKycNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-kyc-names';

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
        $this->line('');

        $this->info('🚀 Starting KYC Verification Process');
        $this->line(str_repeat('-', 50));

        $users = User::where(function ($query) {
            $query->whereNotNull('nin')->where('nin', '<>', '');
        })->where('is_kyc_check', false)
            ->where('is_flagged', false)
            ->get();

        $totalUsers = $users->count();
        $this->info(sprintf('📊 Found %d users requiring KYC verification', $totalUsers));
        $this->line('');

        $progressBar = $this->output->createProgressBar($totalUsers);
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');
        $progressBar->setMessage('Starting...');
        $progressBar->start();

        $processed = 0;
        $successful = 0;
        $failed = 0;
        $flagged = 0;

        foreach ($users as $user) {
            $progressBar->setMessage("Processing User #{$user->id}...");

            try {

                Auth::loginUsingId($user->id);

                $result = MonnifyService::verifyNin($user->nin, NULL, true);

                $result = (array) $result;

                $updatedUser = User::find($user->id);
                $flaggedStatus = $updatedUser->is_flagged ? 'FLAGGED' : 'CLEAN';

                if ($updatedUser->is_flagged)
                    $flagged++;

                if (isset($result['status']) && $result['status']) {
                    $successful++;
                    $this->newLine();
                    $this->line(sprintf(
                        '✅ Successfully verified <fg=green>%s (ID: %d)</> | Status: <fg=cyan>%s</> | KYC: <fg=green>VERIFIED</>',
                        $user->name,
                        $user->id,
                        $flaggedStatus
                    ));
                } else {
                    $failed++;
                    $this->newLine();
                    $this->line(sprintf(
                        '⚠️ Verification failed for <fg=yellow>%s (ID: %d)</> | Status: <fg=cyan>%s</> | Reason: %s',
                        $user->name,
                        $user->id,
                        $flaggedStatus,
                        $result['message'] ?? 'Unknown error'
                    ));
                }


                $processed++;
                $progressBar->advance();

                $delay = rand(5, 7);
                $this->newLine();
                $this->line(sprintf('⏳ Pausing for %d seconds...', $delay));
                sleep($delay);

            } catch (\Exception $e) {
                $failed++;
                $this->newLine();
                $this->error(sprintf(
                    '❌ Error processing <fg=red>%s (ID: %d)</>: %s',
                    $user->name,
                    $user->id,
                    $e->getMessage()
                ));
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info('📋 KYC Verification Summary');
        $this->line(str_repeat('-', 50));
        $this->line(sprintf('• Total Users:     %d', $totalUsers));
        $this->line(sprintf('• Processed:      %d', $processed));
        $this->line(sprintf('• Successful:     %d', $successful));
        $this->line(sprintf('• Failed:         %d', $failed));
        $this->line(sprintf('• Flagged:         %d', $flagged));
        $this->line(sprintf('• Completion:     %d%%', $totalUsers > 0 ? round(($processed / $totalUsers) * 100) : 0));
        $this->line(str_repeat('-', 50));
        $this->info('✨ KYC verification process completed successfully!');
        $this->line('');
    }
}
