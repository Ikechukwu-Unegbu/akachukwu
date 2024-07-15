<?php

namespace Database\Seeders\Education;

use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResultCheckerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vtPass = Vendor::where('name', 'VTPASS')->first();
        $vtPassData = file_get_contents(__DIR__ . '/vtpass.json');
        $vtPassData = json_decode($vtPassData, true);

        collect($vtPassData)->each(function ($data) use ($vtPass) {
            $vtPass->result_checkers()->updateOrCreate(
                ['name' => $data['name']],
                [
                    'amount' => $data['amount'],
                    'status' => $data['status'],
                ]
            );
        });
    }
}
