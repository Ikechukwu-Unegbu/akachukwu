<?php

namespace Database\Seeders\Utility;

use App\Models\Utility\Cable;
use App\Models\Utility\CablePlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CablePlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        CablePlan::truncate();

        $gladtidingsJsonContents = file_get_contents(__DIR__ . '/gladtidings_cable_plan.json');

        $gladtidings = json_decode($gladtidingsJsonContents);

        foreach ($gladtidings->GOTVPLAN as $gotv) {
            CablePlan::create([
                'vendor_id' =>  1,
                'cable_id'  =>  Cable::whereVendorId(1)->whereCableName("GOTV")->first()->cable_id,
                'cable_plan_id' =>  $gotv->cableplan_id,
                'cable_name'    =>  Cable::whereVendorId(1)->whereCableName("GOTV")->first()->cable_name,
                'package'       =>  $gotv->package,
                'amount'        =>  $gotv->plan_amount
            ]);
        }

        foreach ($gladtidings->DSTVPLAN as $dstv) {
            CablePlan::create([
                'vendor_id' =>  1,
                'cable_id'  =>  Cable::whereVendorId(1)->whereCableName("DSTV")->first()->cable_id,
                'cable_plan_id' =>  $dstv->cableplan_id,
                'cable_name'    =>  Cable::whereVendorId(1)->whereCableName("DSTV")->first()->cable_name,
                'package'       =>  $dstv->package,
                'amount'        =>  $dstv->plan_amount
            ]);
        }

        foreach ($gladtidings->STARTIMEPLAN as $startime) {
            CablePlan::create([
                'vendor_id' =>  1,
                'cable_id'  =>  Cable::whereVendorId(1)->whereCableName("STARTIME")->first()->cable_id,
                'cable_plan_id' =>  $startime->cableplan_id,
                'cable_name'    =>  Cable::whereVendorId(1)->whereCableName("STARTIME")->first()->cable_name,
                'package'       =>  $startime->package,
                'amount'        =>  $startime->plan_amount
            ]);
        }


        $postranetJsonContents = file_get_contents(__DIR__ . '/postranet_cable_plan.json');

        $postranet = json_decode($postranetJsonContents);

        foreach ($postranet->GOTVPLAN as $gotv) {
            CablePlan::create([
                'vendor_id' =>  2,
                'cable_id'  =>  Cable::whereVendorId(2)->whereCableName("GOTV")->first()->cable_id,
                'cable_plan_id' =>  $gotv->cableplan_id,
                'cable_name'    =>  Cable::whereVendorId(2)->whereCableName("GOTV")->first()->cable_name,
                'package'       =>  $gotv->package,
                'amount'        =>  $gotv->plan_amount
            ]);
        }

        foreach ($postranet->DSTVPLAN as $dstv) {
            CablePlan::create([
                'vendor_id' =>  2,
                'cable_id'  =>  Cable::whereVendorId(2)->whereCableName("DSTV")->first()->cable_id,
                'cable_plan_id' =>  $dstv->cableplan_id,
                'cable_name'    =>  Cable::whereVendorId(2)->whereCableName("DSTV")->first()->cable_name,
                'package'       =>  $dstv->package,
                'amount'        =>  $dstv->plan_amount
            ]);
        }

        foreach ($postranet->STARTIMEPLAN as $startime) {
            CablePlan::create([
                'vendor_id' =>  2,
                'cable_id'  =>  Cable::whereVendorId(2)->whereCableName("STARTIME")->first()->cable_id,
                'cable_plan_id' =>  $startime->cableplan_id,
                'cable_name'    =>  Cable::whereVendorId(2)->whereCableName("STARTIME")->first()->cable_name,
                'package'       =>  $startime->package,
                'amount'        =>  $startime->plan_amount
            ]);
        }
    }
}
