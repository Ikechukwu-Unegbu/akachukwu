<?php

namespace Database\Seeders\Data;

use App\Models\Data\DataNetwork;
use App\Models\Data\DataType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        DataType::truncate();
        $dataTypes = [
            'CORPORATE', 'SME', 'SME2', 'GIFTING', 'CORPORATE GIFTING', 'DATA COUPONS'
        ];

        foreach (DataNetwork::get() as $network) {
            foreach ($dataTypes as $type) {
                DataType::create([
                    'vendor_id'     =>  $network->vendor_id,
                    'network_id'    =>  $network->network_id,
                    'name'          =>  $type
                ]);
            }
        }
    }
}
