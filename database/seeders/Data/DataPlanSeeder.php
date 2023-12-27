<?php

namespace Database\Seeders\Data;

use App\Models\Data\DataNetwork;
use App\Models\Data\DataPlan;
use App\Models\Data\DataType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DataPlan::truncate();

        $gladtidingsJsonContents = file_get_contents(__DIR__ . '/gladtidings.json');

        $gladtidings = json_decode($gladtidingsJsonContents);

        foreach ($gladtidings->MTN_PLAN->ALL as $plan) {
            DataPlan::create([
                'vendor_id'     =>  1,
                'network_id'    =>  DataNetwork::whereVendorId(1)->whereName($plan->plan_network)->first()->network_id,
                'type_id'       =>  DataType::where('vendor_id', 1)->whereNetworkId(DataNetwork::whereVendorId(1)->whereName($plan->plan_network)->first()->network_id)->where('name', $plan->plan_type)->first()->id,
                'data_id'       =>  $plan->dataplan_id,
                'size'          =>  $plan->plan,
                'amount'        =>  $plan->plan_amount,
                'validity'      =>  $plan->month_validate,
            ]);
        }

        foreach ($gladtidings->GLO_PLAN->ALL as $plan) {
            DataPlan::create([
                'vendor_id'     =>  1,
                'network_id'    =>  DataNetwork::whereVendorId(1)->whereName($plan->plan_network)->first()->network_id,
                'type_id'       =>  DataType::where('vendor_id', 1)->whereNetworkId(DataNetwork::whereVendorId(1)->whereName($plan->plan_network)->first()->network_id)->where('name', $plan->plan_type)->first()->id,
                'data_id'       =>  $plan->dataplan_id,
                'size'          =>  $plan->plan,
                'amount'        =>  $plan->plan_amount,
                'validity'      =>  $plan->month_validate,
            ]);
        }

        foreach ($gladtidings->AIRTEL_PLAN->ALL as $plan) {
            DataPlan::create([
                'vendor_id'     =>  1,
                'network_id'    =>  DataNetwork::whereVendorId(1)->whereName($plan->plan_network)->first()->network_id,
                'type_id'       =>  DataType::where('vendor_id', 1)->whereNetworkId(DataNetwork::whereVendorId(1)->whereName($plan->plan_network)->first()->network_id)->where('name', $plan->plan_type)->first()->id,
                'data_id'       =>  $plan->dataplan_id,
                'size'          =>  $plan->plan,
                'amount'        =>  $plan->plan_amount,
                'validity'      =>  $plan->month_validate,
            ]);
        }

        foreach ($gladtidings->NINEMOBILE_PLAN->ALL as $plan) {
            DataPlan::create([
                'vendor_id'     =>  1,
                'network_id'    =>  DataNetwork::whereVendorId(1)->whereName($plan->plan_network)->first()->network_id,
                'type_id'       =>  DataType::where('vendor_id', 1)->whereNetworkId(DataNetwork::whereVendorId(1)->whereName($plan->plan_network)->first()->network_id)->where('name', $plan->plan_type)->first()->id,
                'data_id'       =>  $plan->dataplan_id,
                'size'          =>  $plan->plan,
                'amount'        =>  $plan->plan_amount,
                'validity'      =>  $plan->month_validate,
            ]);
        }

        $postranetJsonContents = file_get_contents(__DIR__ . '/postranet.json');

        $postranet = json_decode($postranetJsonContents);
        
        foreach ($postranet->MTN_PLAN->ALL as $plan) {
            DataPlan::create([
                'vendor_id'     =>  2,
                'network_id'    =>  DataNetwork::whereVendorId(2)->whereName($plan->plan_network)->first()->network_id,
                'type_id'       =>  DataType::where('vendor_id', 2)->whereNetworkId(DataNetwork::whereVendorId(2)->whereName($plan->plan_network)->first()->network_id)->where('name', $plan->plan_type)->first()->id,
                'data_id'       =>  $plan->dataplan_id,
                'size'          =>  $plan->plan,
                'amount'        =>  $plan->plan_amount,
                'validity'      =>  $plan->month_validate,
            ]);
        }

        foreach ($postranet->GLO_PLAN->ALL as $plan) {
            DataPlan::create([
                'vendor_id'     =>  2,
                'network_id'    =>  DataNetwork::whereVendorId(2)->whereName($plan->plan_network)->first()->network_id,
                'type_id'       =>  DataType::where('vendor_id', 2)->whereNetworkId(DataNetwork::whereVendorId(2)->whereName($plan->plan_network)->first()->network_id)->where('name', $plan->plan_type)->first()->id,
                'data_id'       =>  $plan->dataplan_id,
                'size'          =>  $plan->plan,
                'amount'        =>  $plan->plan_amount,
                'validity'      =>  $plan->month_validate,
            ]);
        }

        foreach ($postranet->AIRTEL_PLAN->ALL as $plan) {
            DataPlan::create([
                'vendor_id'     =>  2,
                'network_id'    =>  DataNetwork::whereVendorId(2)->whereName($plan->plan_network)->first()->network_id,
                'type_id'       =>  DataType::where('vendor_id', 2)->whereNetworkId(DataNetwork::whereVendorId(2)->whereName($plan->plan_network)->first()->network_id)->where('name', $plan->plan_type)->first()->id,
                'data_id'       =>  $plan->dataplan_id,
                'size'          =>  $plan->plan,
                'amount'        =>  $plan->plan_amount,
                'validity'      =>  $plan->month_validate,
            ]);
        }

        foreach ($postranet->NINEMOBILE_PLAN->ALL as $plan) {
            DataPlan::create([
                'vendor_id'     =>  2,
                'network_id'    =>  DataNetwork::whereVendorId(2)->whereName($plan->plan_network)->first()->network_id,
                'type_id'       =>  DataType::where('vendor_id', 2)->whereNetworkId(DataNetwork::whereVendorId(2)->whereName($plan->plan_network)->first()->network_id)->where('name', $plan->plan_type)->first()->id,
                'data_id'       =>  $plan->dataplan_id,
                'size'          =>  $plan->plan,
                'amount'        =>  $plan->plan_amount,
                'validity'      =>  $plan->month_validate,
            ]);
        }
    }
}
