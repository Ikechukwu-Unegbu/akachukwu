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
      

        $postranet = [
            [
                'vendor_id'         =>  2,
                'network_id'        =>  1,
                'name'              =>  'MTN',
                'airtime_discount'  =>  1,
                'data_discount'     =>  1,
            ],
            [
                'vendor_id'         =>  2,
                'network_id'        =>  2,
                'name'              =>  'GLO',
                'airtime_discount'  =>  1,
                'data_discount'     =>  1,
            ],
            [
                'vendor_id'         =>  2,
                'network_id'        =>  3,
                'name'              =>  '9MOBILE',
                'airtime_discount'  =>  1,
                'data_discount'     =>  1,
            ],
            [
                'vendor_id'         =>  2,
                'network_id'        =>  4,
                'name'              =>  'AIRTEL',
                'airtime_discount'  =>  1,
                'data_discount'     =>  1,
            ],
            [
                'vendor_id'         =>  2,
                'network_id'        =>  5,
                'name'              =>  'SMILE',
                'status'            =>  false,
                'airtime_discount'  =>  1,
                'data_discount'     =>  1,
            ],
        ];
        
        foreach ($postranet as $data) {
            DataNetwork::create($data);
        }
    }
}
