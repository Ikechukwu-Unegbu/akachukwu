<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;
use App\Models\VendorServiceMapping;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VendorServiceMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendorId = Vendor::where('status', true)->first()->id;

        VendorServiceMapping::updateOrCreate(['service_type' => 'data'], ['vendor_id' => $vendorId]);
        VendorServiceMapping::updateOrCreate(['service_type' => 'airtime'], ['vendor_id' => $vendorId]);
        VendorServiceMapping::updateOrCreate(['service_type' => 'cable'], ['vendor_id' => $vendorId]);
        VendorServiceMapping::updateOrCreate(['service_type' => 'electricity'], ['vendor_id' => $vendorId]);
    }
}
