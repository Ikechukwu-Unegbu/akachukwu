<?php

namespace Database\Seeders\Palmpay;

use App\Models\Bank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\PalmPayBank;

class PalmpayBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bankFile = __DIR__ . '/bank.json';

        if (File::exists($bankFile)) {
            $banks = json_decode(file_get_contents($bankFile), true);

            $data = collect($banks)->map(function ($bank) {
                return [
                    'name'       => $bank['bankName'] ?? null,
                    'type'       => 'palmpay',  
                    'code'       => $bank['bankCode'] ?? null,
                    'image'      => $bank['bankUrl'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            if (!empty($data)) {
                Bank::insert($data);
            }
        }
    }
}
