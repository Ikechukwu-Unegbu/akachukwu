<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;
use App\Models\Data\DataNetwork;
use App\Models\AirtimeVendorMapping;
use App\Models\VendorServiceMapping;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AirtimeVendorMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AirtimeVendorMapping::truncate();
        
        $vendorId = Vendor::where('status', true)->first()->id;
        $networks = DataNetwork::get()->where('status', true)->pluck('name')->toArray();

        collect($networks)->each(function ($network) use ($vendorId) {
            AirtimeVendorMapping::firstOrCreate([
                'vendor_id'  => $vendorId,
                'network' => $network
            ]);
        });

        VendorServiceMapping::where('service_type', 'airtime')->delete();
    }
}
