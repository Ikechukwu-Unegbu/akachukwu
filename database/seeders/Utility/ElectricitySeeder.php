<?php

namespace Database\Seeders\Utility;

use App\Models\Utility\Electricity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ElectricitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gladtidingsJsonContents = file_get_contents(__DIR__ . '/gladtidings.json');

        $gladtidings = json_decode($gladtidingsJsonContents);

        foreach ($gladtidings->electricity as $electricity) {
            Electricity::create([
                'vendor_id'     =>  1,
                'disco_id'      =>  $electricity->disco_id,
                'disco_name'    =>  $electricity->disco_name,
                'status'        =>  $electricity->status,
            ]);
        }


        $postranetJsonContents = file_get_contents(__DIR__ . '/postranet.json');

        $postranet = json_decode($postranetJsonContents);

        foreach ($postranet->electricity as $electricity) {
            Electricity::create([
                'vendor_id'     =>  2,
                'disco_id'      =>  $electricity->disco_id,
                'disco_name'    =>  $electricity->disco_name,
                'status'        =>  $electricity->status,
            ]);
        }
    }
}
