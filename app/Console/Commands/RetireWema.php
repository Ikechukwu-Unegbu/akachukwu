<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\VirtualAccount;
use App\Services\Money\BasePalmPayService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RetireWema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:retire-wema';

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
        $this->info('deleting wema now - soft delete');
        VirtualAccount::where('bank_name', 'Wema bank')->delete();

        $this->info('Creating palmpay now.');
        if(app()->environment() == 'production'){
            User::whereDoesntHave('virtualAccounts', function ($query) {
                $query->where('bank_name', 'Palmpay');
            })
            ->isKYCValidated()
            ->chunk(50, function ($users) {
                foreach ($users as $user) {
                    // Process each user
                    // Example: Log or perform an action
                    $palmpayBase = new BasePalmPayService();
                    $palmpayBase->createSpecificVirtualAccount($user);
                    $this->info(" a chunk is done");

                    sleep(5);
                }
            });
        }
        $this->info('all done');

    }
}
