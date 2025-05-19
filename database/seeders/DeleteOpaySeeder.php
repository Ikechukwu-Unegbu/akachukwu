<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PalmPayBank;
use App\Models\Bank;

class DeleteOpaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bank = Bank::where('type', 'palmpay')->where('code', '355555')->where('name', 'OPAY')->first();
        $bank->delete();
    }
}
