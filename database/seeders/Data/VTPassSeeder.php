<?php

namespace Database\Seeders\Data;

use App\Models\Vendor;
use Illuminate\Support\Str;
use App\Models\Utility\Cable;
use Illuminate\Database\Seeder;
use App\Models\Data\DataNetwork;
use App\Models\Data\DataPlan;
use App\Models\Data\DataType;
use App\Models\Utility\CablePlan;
use App\Models\Utility\Electricity;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class VTPassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $networks = [
                [
                    'network_id' =>  1,
                    'name'       =>  'MTN',
                    'status'     =>  true
                ],
                [
                    'network_id' =>  2,
                    'name'       =>  'GLO',
                    'status'     =>  true
                ],
                [
                    'network_id' =>  3,
                    'name'       =>  'AIRTEL',
                    'status'     =>  true
                ],
                [
                    'network_id' =>  4,
                    'name'       =>  '9MOBILE',
                    'status'     =>  false
                ]
            ];

            $dataTypes = [
                'CORPORATE',
                'SME'
            ];
    
            $vendor = Vendor::where('name', 'VTPASS')->first();
    
            $jsonPath = __DIR__ . '/vtpass_data.json';
            $jsonData = File::get($jsonPath);
            $serviceData = json_decode($jsonData, true);
    
            foreach ($networks as $network) {
                $dataNetwork = DataNetwork::firstOrCreate([
                    'vendor_id' => $vendor->id, 
                    'network_id' =>  $network['network_id'], 
                    'name' => $network['name'],
                    'status' => $network['status'],
                    'airtime_discount'  =>  1,
                    'data_discount'     =>  1,
                ]);

                foreach ($dataTypes as $type) {
                    $dataType = DataType::firstOrCreate([
                        'vendor_id' =>  $vendor->id,
                        'network_id'=>   $dataNetwork->network_id,
                        'name'      =>  $type
                    ]);
                }
            }

            foreach ($serviceData as $index => $services) {
                $dataNetwork = DataNetwork::where('vendor_id', $vendor->id)->where('name', $index)->first();
                foreach ($services as  $service) {
                    foreach ($service as $key => $__service) {                        
                        foreach ($__service as $data) {
                            $size = "";
                            $string = $data['name'];

                            if (strpos($string, 'Xtra') !== false) {
                                if (preg_match('/N([0-9,]+)/', $string, $matches)) {
                                    $size =  $matches[0] . "\n";
                                }
                            }
                        
                            if (preg_match('/([0-9.]+ ?(?:TB|MB|mb|GB))/', $string, $matches)) {
                                $size =  $matches[0] . "\n";
                            }

                            $serviceId = Str::lower($dataNetwork->name) . '-data';

                            if ($dataNetwork->name === "GLO" && $key === "SME") {
                                $serviceId = "glo-sme-data";
                            }

                            if ($dataNetwork->name === "9MOBILE" && $key === "SME") {
                                $serviceId = "9mobile-sme-data";
                            }

                            if ($dataNetwork->name === "9MOBILE" && $key === "CORPORATE") {
                                $serviceId = "etisalat-data";
                            }

                            $dataType = DataType::where(['vendor_id' => $vendor->id, 'network_id' => $dataNetwork->network_id, 'name' => $key])->first();

                            DataPlan::firstOrCreate([
                                'vendor_id' =>  $vendor->id,
                                'network_id'=>  $dataNetwork->network_id,
                                'data_id'   =>  $data['variation_code'],
                                'amount'    =>  $data['variation_amount'],
                                'validity'  =>  $data['name'],
                                'type_id'   =>  $dataType->id,
                                'size'      =>  $size,
                                'service_id'=>  $serviceId
                            ]);
                        }
                    }
                }
            }
    
            $electricityJsonPath = __DIR__ . '/vtpass_electricity.json';
            $electricityJsonData = File::get($electricityJsonPath);
            $electricityServiceData = collect(json_decode($electricityJsonData, true));
    
            $electricityServiceData->each(function ($data) use ($vendor) {
                Electricity::firstOrCreate([
                    'vendor_id' =>  $vendor->id,
                    'disco_id'  =>  $data['id'],
                    'disco_name'=>  $data['name'],
                    'discount'      =>1,
                ], ['status'  =>  $data['status']]);
            });
    
            $cableJsonPath = __DIR__ . '/vtpass_cable.json';
            $cableJsonData = File::get($cableJsonPath);
            $cableServiceData = json_decode($cableJsonData, true);
    
            foreach ($cableServiceData as $index => $services) {
                $cable = Cable::firstOrCreate([
                    'vendor_id' => $vendor->id,
                    'cable_name'  => $index,
                    'cable_id'  => Str::lower($index),
                    'discount'      =>1,
                    'status'    =>  true
                ]);
    
                foreach ($services as $service) {
                    $cablePlan = CablePlan::firstOrCreate([
                        'vendor_id'     =>  $vendor->id,
                        'cable_id'      =>  $cable->id,
                        'cable_name'    =>  $cable->cable_name,
                        'cable_plan_id' =>  $service['variation_code'],
                        'package'       =>  $service['name'],
                        'amount'        =>  $service['variation_amount'],
                        'status'        =>  true
                    ]);

                    $cablePlan->update(['cable_id' => $cable->cable_id]);
                }
            }
        });
      
    }
}
