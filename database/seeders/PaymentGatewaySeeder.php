<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentGateway::create([
            'name'     => 'Paystack',
            'key'      => 'sk_test_6e813e008627ceee06b8b6cdf70626190d62ec20',
            'status'   =>   true
        ]);

        PaymentGateway::create([
            'name'     => 'Flutterwave',
            'key'      => 'FLWSECK_TEST-6590404d1e4f6a72fa77a099bfb78422-X',
            'status'   =>   true
        ]);
    }
}
