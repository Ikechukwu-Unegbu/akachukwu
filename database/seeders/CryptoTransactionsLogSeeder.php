<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment\CryptoTransactionsLog;
use App\Models\User;

class CryptoTransactionsLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::firstOrCreate(
        //     ['id' => 6],
        //     [
        //         'name' => 'Test User',
        //         'username'=>'restuser',
        //         'email' => 'testuser@example.com',
        //         'password' => bcrypt('password'), // or Hash::make
        //         'role'=>0
        //     ]
        // );

        CryptoTransactionsLog::create([
            'txid'             => null,
            'transaction_id'   => '1e0c1666-4a9f-4a4c-84b0-b8ff1fa9a43d',
            'session_id'       => null,
            'order_no'         => null,
            'user_id'          => 6,
            'amount'           => '742.19',
            'amount_in_crypto' => '0.49000000',
            'fee'              => '0.00',
            'currency'         => 'usdt',
            'crypto_currency'  => 'btc',
            'wallet_address'   => null,
            'remark'           => null,
            'balance_before'   => '0.00',
            'balance_after'    => '0.00',
            'status'           => 'accepted',
            'meta'             => json_encode([
                "id" => "1e0c1666-4a9f-4a4c-84b0-b8ff1fa9a43d",
                "fee" => "0.0",
                "txid" => null,
                "type" => "internal",
                "user" => [
                    "id" => "f723ef71-d748-4a54-bcf5-b1a6f0de2453",
                    "sn" => "QDXO2VRBQWM",
                    "email" => "ikunegbu@ebsafr.com",
                    "last_name" => "Vincent",
                    "reference" => null,
                    "created_at" => "2025-09-04T11:19:12.000+01:00",
                    "first_name" => "Ikechukwu",
                    "updated_at" => "2025-09-04T11:19:12.000+01:00",
                    "display_name" => null
                ],
                "amount" => "0.49",
                "reason" => null,
                "sender" => "West Baylee",
                "status" => "accepted",
                "wallet" => [
                    "id" => "0699b3fa-e42d-4b73-8641-236d9cf18604",
                    "name" => "USDT Tether",
                    "user" => [
                        "id" => "f723ef71-d748-4a54-bcf5-b1a6f0de2453",
                        "sn" => "QDXO2VRBQWM",
                        "email" => "ikunegbu@ebsafr.com",
                        "last_name" => "Vincent",
                        "reference" => null,
                        "created_at" => "2025-09-04T11:19:12.000+01:00",
                        "first_name" => "Ikechukwu",
                        "updated_at" => "2025-09-04T11:19:12.000+01:00",
                        "display_name" => null
                    ],
                    "locked" => "0.0",
                    "staked" => "0.0",
                    "balance" => "0.49",
                    "currency" => "usdt",
                    "networks" => [
                        ["id" => "bep20", "name" => "Binance Smart Chain", "deposits_enabled" => true, "withdraws_enabled" => true],
                        ["id" => "erc20", "name" => "Ethereum Network", "deposits_enabled" => true, "withdraws_enabled" => true],
                        ["id" => "trc20", "name" => "Tron Network", "deposits_enabled" => true, "withdraws_enabled" => true],
                        ["id" => "polygon", "name" => "Polygon Network", "deposits_enabled" => true, "withdraws_enabled" => true],
                        ["id" => "solana", "name" => "Solana Network", "deposits_enabled" => true, "withdraws_enabled" => true],
                        ["id" => "celo", "name" => "Celo Network", "deposits_enabled" => true, "withdraws_enabled" => true],
                        ["id" => "optimism", "name" => "Optimism Network", "deposits_enabled" => true, "withdraws_enabled" => true],
                        ["id" => "ton", "name" => "Ton Network", "deposits_enabled" => true, "withdraws_enabled" => true],
                        ["id" => "arbitrum", "name" => "Arbitrum Network", "deposits_enabled" => true, "withdraws_enabled" => false],
                    ],
                    "is_crypto" => true,
                    "created_at" => "2025-09-04T11:19:12.000+01:00",
                    "updated_at" => "2025-09-17T13:30:27.000+01:00",
                    "default_network" => "bep20",
                    "deposit_address" => "0xc5531C289B6baFc3106dC11a2Deb084f85E1c35e",
                    "destination_tag" => null,
                    "converted_balance" => "742.1883",
                    "blockchain_enabled" => true,
                    "reference_currency" => "ngn"
                ],
                "done_at" => "2025-09-17T13:30:27.000+01:00",
                "currency" => "usdt",
                "created_at" => "2025-09-17T13:30:27.000+01:00"
            ]),
            'api_response'     => null,
            'created_at'       => '2025-09-17 14:30:31',
            'updated_at'       => '2025-09-17 14:30:31',
        ]);
    }
}
