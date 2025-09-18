<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment\CryptoWallet;

class GenerateCryptoWalletsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-crypto-wallets';

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
        $users = \App\Models\User::all();
        foreach($users as $user){
            if(!$user->quidax_id){
                    CryptoWallet::create([
                    'user_id' => $user->id,
                    'currency' => CryptoWallet::NGN,
                    'wallet_type' => 'crypto_base',
                    'balance' => 0.0000000,
                    
                ]);
            }
        }
    }
}
