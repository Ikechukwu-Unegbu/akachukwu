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
        // PaymentGateway::truncate();

        $paymentGateways = collect([
            [
                'name'          =>  'Paystack',
                'key'           =>  'sk_test_6e813e008627ceee06b8b6cdf70626190d62ec20',
                'public_key'    =>  'pk_test_c2355a3c098d2834c70789eb903ec2eb5ec8cdb9',
                'contract_code'  =>  null,
                'account_number' =>  null,
                'status'         =>  false
            ],
            [
                'name'          =>  'Flutterwave',
                'key'           =>  'FLWSECK_TEST-6590404d1e4f6a72fa77a099bfb78422-X',
                'public_key'    =>  'FLWPUBK_TEST-f9079010757deb5cee6bff35fe7e07e1-X',
                'contract_code'  =>  null,
                'account_number' =>  null,
                'status'         =>  false
            ],
            [
                'name'           =>  'Monnify',
                'key'            =>  'RSULLT255Y8E4YKHWRUG78JPLM5SNBXA',
                'public_key'     =>  'MK_TEST_EN7LKTHNUF',
                'contract_code'  =>  7851651278,
                'account_number' =>  2391862286,
                'status'         =>  true
            ],
            [
                'name'           =>  'Payvessel',
                'key'            =>  'PVSECRET-OZJD0SZ2F2WOTXAF',
                'public_key'     =>  'PVKEY-3ZO1QOSQH83C5Q3PBCVUT1',
                'contract_code'  =>  null,
                'account_number' =>  null,
                'status'         =>  false,
            ],
             [
                'name'           =>  'Palmpay',
                'key'            =>  '',
                'public_key'     =>  '',
                'contract_code'  =>  null,
                'account_number' =>  null,
                'status'         =>  false,
            ]
        ]);

        $paymentGateways->each(function ($gateway) {
            PaymentGateway::firstOrCreate(['name' => $gateway['name']], [
                'key'            =>  $gateway['key'],
                'public_key'     =>  $gateway['public_key'],
                'contract_code'  =>  $gateway['contract_code'],
                'account_number' =>  $gateway['account_number'],
                'status'         =>  $gateway['status'],
            ]);
        });      
    }
}
