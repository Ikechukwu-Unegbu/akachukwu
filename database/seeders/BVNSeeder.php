<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use App\Models\User;
use App\Models\VirtualAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BVNSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $virtualAccounts = VirtualAccount::get();
        $monnifyId = PaymentGateway::where('name', 'Monnify')->first()->id;
        
        foreach ($virtualAccounts as $virtualAccount) {

            if (!empty($virtualAccount->bvn)) {
                $virtualAccount->user->update(['bvn' => $virtualAccount->bvn]);
            }

            if ($virtualAccount->collection_channel === "RESERVED_ACCOUNT") {
                $virtualAccount->update(['payment_id' => $monnifyId]);
            }

        }
    }
}
