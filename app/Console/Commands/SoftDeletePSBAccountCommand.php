<?php

namespace App\Console\Commands;

use App\Models\VirtualAccount;
use Illuminate\Console\Command;

class SoftDeletePSBAccountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'virtual-accounts:delete-soft';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Soft delete virtual accounts based on bank name or code';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Query records matching the criteria
        $count = VirtualAccount::where('bank_code', 120001)
            ->delete();

        $this->info("{$count} virtual accounts soft-deleted successfully.");
    }
}
