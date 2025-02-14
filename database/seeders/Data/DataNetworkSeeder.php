<?php

namespace Database\Seeders\Data;

use App\Models\Data\DataNetwork;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataNetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DataNetwork::truncate();
        $glad_tidings = [
            [
                'vendor_id'     =>  1,
                'airtime_discount' => 0.0,
                'data_discount' => 0.0,
                'network_id'    =>  1,
                'name'          =>  'MTN',
            ],
            [
                'vendor_id'     =>  1,
                'airtime_discount' => 0.0,
                'data_discount' => 0.0,
                'network_id'    =>  2,
                'name'          =>  'GLO',
            ],
            [
                'vendor_id'     =>  1,
                'airtime_discount' => 0.0,
                'data_discount' => 0.0,
                'network_id'    =>  3,
                'name'          =>  'AIRTEL',
            ],
            [
                'vendor_id'     =>  1,
                'airtime_discount' => 0.0,
                'data_discount' => 0.0,
                'network_id'    =>  6,
                'name'          =>  '9MOBILE',
            ],
            [
                'vendor_id'     =>  1,
                'airtime_discount' => 0.0,
                'data_discount' => 0.0,
                'network_id'    =>  7,
                'name'          =>  'SMILE',
                'status'        =>  false
            ],
        ];

        foreach ($glad_tidings as $data) {
            DataNetwork::create($data);
        }

        $postranet = [
            [
                'vendor_id'     =>  2,
                'airtime_discount' => 0.0,
                'data_discount' => 0.0,
                'network_id'    =>  1,
                'name'          =>  'MTN',
            ],
            [
                'vendor_id'     =>  2,
                'airtime_discount' => 0.0,
                'data_discount' => 0.0,
                'network_id'    =>  2,
                'name'          =>  'GLO',
            ],
            [
                'vendor_id'     =>  2,
                'airtime_discount' => 0.0,
                'data_discount' => 0.0,
                'network_id'    =>  3,
                'name'          =>  '9MOBILE',
            ],
            [
                'vendor_id'     =>  2,
                'airtime_discount' => 0.0,
                'data_discount' => 0.0,
                'network_id'    =>  4,
                'name'          =>  'AIRTEL',
            ],
            [
                'vendor_id'     =>  2,
                'airtime_discount' => 0.0,
                'data_discount' => 0.0,
                'network_id'    =>  5,
                'name'          =>  'SMILE',
                'status'        =>  false
            ],
        ];
        foreach ($postranet as $data) {
            DataNetwork::create($data);
        }

    }
}