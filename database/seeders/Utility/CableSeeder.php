<?php

namespace Database\Seeders\Utility;

use App\Models\Utility\Cable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cable::truncate();
        $cables = [
            1 => "GOTV",
            2 => "DSTV",
            3 => "STARTIME"
        ];

        foreach ($cables as $key => $cable) {
            Cable::create([
                'vendor_id'     =>  1,
                'cable_id'      =>  $key,
                'cable_name'    =>  $cable,
                'status'        =>  true
            ]);            
        }

        foreach ($cables as $key => $cable) {
            Cable::create([
                'vendor_id'     =>  2,
                'cable_id'      =>  $key,
                'cable_name'    =>  $cable,
                'status'        =>  true
            ]);
        }
    }
}
