<?php

namespace Database\Seeders\Data;

use App\Models\Vendor;
use App\Models\Data\DataVendor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DataVendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = collect([
            [
                'name' => 'GLADTIDINGSDATA', 
                'api' => 'https://gladtidingsdata.com/api', 
                'token' => 'b461c35fc899542855bc513ac793e4b4f9effe93', 
                'public_key' => null,
                'secret_key' => null,
                'status' => false
            ],
            [
                'name' => 'POSTRANET', 
                'api' => 'https://postranet.com/api', 
                'token' => '429ddaa2f18dbc5618ee968452adabe5b11c84cd', 
                'public_key' => null,
                'secret_key' => null,
                'status' => true
            ],
            [
                'name' => 'VTPASS', 
                'api' => 'https://api-service.vtpass.com/api/pay', 
                'token' => 'bc997b23f90a2fa44517b898eb161d5d', 
                'public_key' => "PK_178f7063791f5402c274227db7bfc4a0ecb3b055a8a",
                'secret_key' => "SK_2418ce9dc110a89bdea6d7a42b7fcbed05d5ea1be41",
                'status' => false
            ]
        ]);

        $vendors->each(function ($vendor) {
            Vendor::firstOrCreate(
                ['name' => $vendor['name']], 
                [
                    'api' => $vendor['api'], 
                    'token' => $vendor['token'], 
                    'public_key' => $vendor['public_key'], 
                    'secret_key' => $vendor['secret_key'], 
                    'status' => $vendor['status']
                ]
            );
        });
    }
}
