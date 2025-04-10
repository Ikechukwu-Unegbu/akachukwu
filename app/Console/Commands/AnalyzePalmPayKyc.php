<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\VirtualAccount;
class AnalyzePalmPayKyc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:analyze-palmpay-kyc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze KYC completion for users since PalmPay integration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Get the PalmPay integration date (first PalmPay account creation)
        $palmpayIntegrationDate = VirtualAccount::where('bank_name', 'PalmPay')
            ->orderBy('created_at')
            ->value('created_at');

        if (!$palmpayIntegrationDate) {
            $this->error('No PalmPay accounts found in the system!');
            return 1;
        }

        $this->info("PalmPay integration date: " . $palmpayIntegrationDate);

        // 2. Calculate all metrics
        $totalUsers = User::where('created_at', '>=', $palmpayIntegrationDate)->count();
        $kycUsers = User::where('created_at', '>=', $palmpayIntegrationDate)
            ->where(function($q) {
                $q->whereNotNull('nin')->orWhereNotNull('bvn');
            })->count();
        
        $kycWithPalmpay = User::where('created_at', '>=', $palmpayIntegrationDate)
            ->where(function($q) {
                $q->whereNotNull('nin')->orWhereNotNull('bvn');
            })
            ->whereHas('virtualAccounts', function($q) {
                $q->where('bank_name', 'PalmPay');
            })->count();

        $palmpayWithoutKyc = User::where('created_at', '>=', $palmpayIntegrationDate)
            ->whereNull('nin')
            ->whereNull('bvn')
            ->whereHas('virtualAccounts', function($q) {
                $q->where('bank_name', 'PalmPay');
            })->count();

        // 3. Display results in a table
        $this->table(
            ['Metric', 'Count', 'Percentage'],
            [
                ['Total users since integration', $totalUsers, '100%'],
                ['Users with KYC (NIN or BVN)', $kycUsers, $this->percentage($kycUsers, $totalUsers)],
                ['Users with KYC AND PalmPay', $kycWithPalmpay, $this->percentage($kycWithPalmpay, $totalUsers)],
                ['Users with PalmPay but NO KYC', $palmpayWithoutKyc, $this->percentage($palmpayWithoutKyc, $totalUsers)],
            ]
        );

        // 4. Additional insights
        $this->newLine();
        $this->info("Additional Insights:");
        $this->line("- KYC completion rate among PalmPay users: " . $this->percentage($kycWithPalmpay, ($kycWithPalmpay + $palmpayWithoutKyc)));
        $this->line("- PalmPay adoption rate: " . $this->percentage(($kycWithPalmpay + $palmpayWithoutKyc), $totalUsers));

        return 0;
    }

    protected function percentage($part, $total)
    {
        return $total > 0 ? round(($part / $total) * 100, 2) . '%' : '0%';
    }
}
