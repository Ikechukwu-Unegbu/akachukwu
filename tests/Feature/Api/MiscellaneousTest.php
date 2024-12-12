<?php

namespace Tests\Feature\Api;

use App\Models\Utility\Electricity;
use Database\Seeders\Data\DataNetworkSeeder;
use Database\Seeders\Data\DataPlanSeeder;
use Database\Seeders\Data\DataTypeSeeder;
use Database\Seeders\Data\DataVendorSeeder;
use Database\Seeders\Money\MoneyTransferSeeder;
use Database\Seeders\Utility\CablePlanSeeder;
use Database\Seeders\Utility\CableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Database\Seeders\Utility\ElectricitySeeder;
use Database\Seeders\VendorServiceMappingSeeder;
use Illuminate\Support\Facades\Artisan;
// use Illuminate\Foundation\Testing\RefreshDatabase;

class MiscellaneousTest extends TestCase
{
  
    use RefreshDatabase;

    public function test_that_disco_list_api_works(){
        Artisan::call('db:seed', ['--class' => DataVendorSeeder::class]);
        Artisan::call('db:seed', ['--class' => ElectricitySeeder::class]);
        Artisan::call('db:seed', ['--class' => VendorServiceMappingSeeder::class]);

        $response = $this->postJson('api/electricity/discos');

        // Assert the response status and format
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'response',
            'message',
        ]);

    }

   public function test_that_data_types_list_api_works()
   {
        Artisan::call('db:seed', ['--class' => DataVendorSeeder::class]);
        Artisan::call('db:seed', ['--class' => DataNetworkSeeder::class]);
        Artisan::call('db:seed', ['--class' => VendorServiceMappingSeeder::class]);

        $response = $this->postJson('api/datatypes', [
            "network_id" => 3
        ]);
        

        // Assert the response status and format
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'response',
            'message',
        ]);
   }

   public function test_that_bank_list_api_works()
   {
        Artisan::call('db:seed', ['--class' => MoneyTransferSeeder::class]);
    
        $response = $this->getJson('api/banks');
        
        // Assert the response status and format
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'response',
            'message',
        ]);
   }

   public function test_that_cable_ist_api_works()
   {
        Artisan::call('db:seed', ['--class' => CableSeeder::class]);
        Artisan::call('db:seed', ['--class' => CablePlanSeeder::class]);
        
        $response = $this->postJson('api/cables');
        
        // Assert the response status and format
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'response',
            'message',
        ]);
   }

   public function test_that_datatypes_list_api_works()
   {
    
   }

   public function test_that_cable_plans_list_api_works()
   {
        Artisan::call('db:seed', ['--class' => CableSeeder::class]);
        Artisan::call('db:seed', ['--class' => CablePlanSeeder::class]);
        
        $response = $this->postJson('api/cableplans', [
            'cable_id'=>2
        ]);
        
        // Assert the response status and format
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'response',
            'message',
        ]);
   }

   public function test_that_cable_plans_list_api_throws_validation_error_without_cable_id()
    {
        Artisan::call('db:seed', ['--class' => CableSeeder::class]);
        Artisan::call('db:seed', ['--class' => CablePlanSeeder::class]);
        
        $response = $this->postJson('api/cableplans', []);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('cable_id');
    }

    public function test_that_data_plans_list_api_throws_validation_error_without_plan_id()
    {
        Artisan::call('db:seed', ['--class' => DataVendorSeeder::class]);
        Artisan::call('db:seed', ['--class' => DataTypeSeeder::class]);
        Artisan::call('db:seed', ['--class' => DataPlanSeeder::class]);
        Artisan::call('db:seed', ['--class' => VendorServiceMappingSeeder::class]);
        
        $response = $this->postJson('api/dataplans', []);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('data_type_id');
    }


   
}
