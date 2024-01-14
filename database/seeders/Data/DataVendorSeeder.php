<?php

namespace Database\Seeders\Data;

use App\Models\Data\DataVendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataVendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DataVendor::create([
            'name'      =>  'GLADTIDINGSDATA',
            'api'       =>  'https://gladtidingsdata.com/api',
            'token'     =>  'b461c35fc899542855bc513ac793e4b4f9effe93',
            'status'    =>  false
        ]);

        DataVendor::create([
            'name'      =>  'POSTRANET',
            'api'       =>  'https://postranet.com/api',
            'token'     =>  '429ddaa2f18dbc5618ee968452adabe5b11c84cd',
            'status'    =>  true
        ]);
    }
}
