<?php

namespace Database\Seeders;

use App\Models\Bank;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\MoneyTransfer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MoneyTransferRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Get some users to use as senders/recipients
        $users = User::take(10)->get();

        if ($users->isEmpty()) {
            $this->command->error('No users found in database. Please run UserSeeder first.');
            return;
        }

        // Get banks from database using your method
        $banks = Bank::isPalmPay()->get()->map(function ($q) {
            return [
                'code' => $q->code,
                'name' => $q->name
            ];
        })->toArray();

        if (empty($banks)) {
            $this->command->error('No banks found in database. Please seed banks first.');
            return;
        }

        $statuses = ['successful', 'processing', 'pending', 'failed', 'refunded'];

        for ($i = 1; $i <= 10; $i++) {
            $sender = $users->random();
            $recipient = $users->where('id', '!=', $sender->id)->random();
            $bank = $banks[array_rand($banks)];
            $status = $statuses[array_rand($statuses)];
            $amount = mt_rand(1000, 50000) + (mt_rand(0, 99) / 100);
            $charges = $amount * 0.015;
            $senderBalanceBefore = mt_rand(50000, 200000) + (mt_rand(0, 99) / 100);
            $senderBalanceAfter = $senderBalanceBefore - $amount - $charges;
            $recipientBalanceBefore = mt_rand(10000, 100000) + (mt_rand(0, 99) / 100);
            $recipientBalanceAfter = $recipientBalanceBefore + $amount;

            $createdAt = Carbon::now()->subDays(mt_rand(0, 30))->subHours(mt_rand(0, 23));

            $moneyTransferRecord[] = [
                'user_id' => $sender->id,
                'reference_id' => 'TRX' . strtoupper(uniqid()),
                'trx_ref' => 'REF' . mt_rand(100000, 999999),
                'amount' => $amount,
                'sender_balance_before' => $senderBalanceBefore,
                'sender_balance_after' => $senderBalanceAfter,
                'recipient_balance_before' => $recipientBalanceBefore,
                'recipient_balance_after' => $recipientBalanceAfter,
                'narration' => 'Transfer to ' . $recipient->name,
                'bank_code' => $bank['code'],
                'bank_name' => $bank['name'],
                'account_number' => '0' . mt_rand(100000000, 999999999),
                'comment' => 'Transaction processed',
                'currency' => 'NGN',
                'status' => $status === 'successful' ? 1 : 0,
                'transfer_status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
                'recipient' => $recipient->id,
                'type' => fake()->randomElement(['external', 'internal']),
                'meta' => json_encode([
                    'payeeName' => $recipient->name,
                    'bankCode' => $bank['code'],
                ]),
                'api_response' => json_encode([
                    'data' => [
                        'message' => $status === 'successful' ? 'Transaction successful' : 'Transaction pending',
                        'amount' => $amount * 100,
                        'fee' => [
                            'fee' => $charges * 100,
                            'vat' => $charges * 0.075 * 100,
                        ],
                        'orderId' => 'ORD' . mt_rand(100000, 999999),
                        'orderNo' => 'INV' . mt_rand(100000, 999999),
                        'sessionId' => 'SESS' . mt_rand(100000, 999999),
                        'orderStatus' => $status === 'successful' ? 2 : 1,
                        'respMsg' => $status === 'successful' ? 'Approved' : 'Pending',
                        'respCode' => $status === 'successful' ? '00' : '09',
                    ]
                ]),
                'balance_after_refund' => 0.00,
                'charges' => $charges,
                'note' => 'Transaction note ' . $i,
            ];
        }

        MoneyTransfer::insert($moneyTransferRecord);

        $this->command->info('Successfully created 10 money transfer records.');
    }
}
