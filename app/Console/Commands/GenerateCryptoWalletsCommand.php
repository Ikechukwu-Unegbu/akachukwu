<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment\CryptoWallet;
use App\Models\User;

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
    protected $description = 'Ensure each user has exactly one base crypto wallet';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        $created = 0;
        $cleaned = 0;

        foreach ($users as $user) {
            $wallets = $user->cryptoWallets()->get(); // assuming relation exists

            if ($wallets->count() === 0) {
                // Create new wallet
                CryptoWallet::create([
                    'user_id'     => $user->id,
                    'currency'    => CryptoWallet::USDT, // pick your default currency
                    'wallet_type' => 'crypto_base',
                    'balance'     => 0.0,
                ]);
                $created++;
            } elseif ($wallets->count() > 1) {
                // Keep the first wallet, delete the rest
                $walletsToDelete = $wallets->slice(1);
                foreach ($walletsToDelete as $wallet) {
                    $wallet->delete();
                }
                $cleaned++;
            }
        }

        $this->info("✅ {$created} wallets created, {$cleaned} users had duplicates cleaned.");
    }
}
