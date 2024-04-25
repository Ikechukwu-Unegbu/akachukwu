<?php

namespace Database\Seeders\Money;

use App\Models\Bank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MoneyTransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = file_get_contents(__DIR__ . '/banks.json');

        $banks = json_decode($banks);


        foreach ($banks as $bank) {
            Bank::create([
                'name'                  =>   $bank->name,
                'code'                  =>   $bank->code,
                'ussd_template'         =>   $bank->ussdTemplate,
                'base_ussd_code'        =>   $bank->baseUssdCode,
                'transfer_ussd_template'=>   $bank->transferUssdTemplate,
                'bank_id'               =>   $bank->bankId,
                'nip_bank_code'         =>   $bank->nipBankCode
            ]);
        }
    }
}
