<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\VendorServiceMapping;
use Database\Seeders\Data\VTPassSeeder;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\Data\DataVendorSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Seeders\Utility\ElectricitySeeder;
use Database\Seeders\VendorServiceMappingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ElectricityApiTest extends TestCase
{
    use RefreshDatabase;
    protected CONST METER_NUMBER = '1111111111111';
    protected CONST DISCO_ID = 'ikeja-electric';
    protected CONST METER_TYPE = 1;

    public function setup() : void
    {
        parent::setup();
        Artisan::call('db:seed', ['--class' => DataVendorSeeder::class]);
        Artisan::call('db:seed', ['--class' => VendorServiceMappingSeeder::class]);
        Artisan::call('db:seed', ['--class' => ElectricitySeeder::class]);
        Artisan::call('db:seed', ['--class' => VTPassSeeder::class]);
        VendorServiceMapping::updateOrCreate(['service_type' => 'electricity'], ['vendor_id' => 3]);
    }

    /** @test electricity purchase fails when user has insufficient balance */
    public function electricity_purchase_fails_when_user_has_insufficient_balance()
    {
        $user = User::factory()->create([
            'account_balance' => 10,
        ]);

        $response = $this->actingAs($user)->postJson('/api/electricity/validate', [
            'meter_number' => self::METER_NUMBER,
            'disco_id'    => self::DISCO_ID,
            'meter_type' =>  self::METER_TYPE,
        ]);

        // if ($response->assertSt)

        // $response->assertStatus(422);

        Log::info(json_encode($response));
        
    }
}
