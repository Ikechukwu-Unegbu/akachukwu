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

    public function setup() : void
    {
        parent::setup();
        Artisan::call('db:seed', ['--class' => DataVendorSeeder::class]);
        Artisan::call('db:seed', ['--class' => VendorServiceMappingSeeder::class]);
        Artisan::call('db:seed', ['--class' => ElectricitySeeder::class]);
        Artisan::call('db:seed', ['--class' => VTPassSeeder::class]);
    }

    /** @test electricity purchase fails when user has insufficient balance */
    public function electricity_purchase_fails_when_user_has_insufficient_balance()
    {
        try {
            $user = User::factory()->create([
                'account_balance' => 10,
            ]);

            // Set up test data
            $requestPayload = [
                'amount'        => 1000,
                'meter_number'  => '1111111111111',
                'disco_id'      => 'ikeja-electric',
                'meter_type'    => '1',
                'phone_number'  => '08130974397',
            ];

            $validateResponse = $this->actingAs($user)->postJson('/api/electricity/validate', [
                'meter_number' => $requestPayload['meter_number'],
                'disco_id'     => $requestPayload['disco_id'],
                'meter_type'   => $requestPayload['meter_type'],
            ]);

            $validateResponse->assertStatus(200)->assertJsonStructure([
               'status',
               'response' => ['name', 'address'],
               'message'
            ]);

            $validatedData = $validateResponse->json('response');
           
            $purchaseResponse = $this->actingAs($user)->postJson('/api/electricity/create', array_merge($requestPayload, [
                'owner_name'    => $validatedData['name'],
                'owner_address' => $validatedData['address'],
            ]));

            $purchaseResponse->assertJson([
                'status' => false,
                'message' => 'Your account balance is insufficient to complete this transaction.',
            ]);

        } catch (\Exception $e) {
            Log::error("electricity purchase fails when user has insufficient balance",  [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /** @test electricity purchase is successful when user has sufficient balance */
    public function electricity_purchase_is_successful_when_user_has_sufficient_balance()
    {
        try {
            $user = User::factory()->create([
                'account_balance' => 1000,
            ]);

            // Set up test data
            $requestPayload = [
                'amount'        => 500,
                'meter_number'  => '1111111111111',
                'disco_id'      => 'ikeja-electric',
                'meter_type'    => '1',
                'phone_number'  => '08130974397',
            ];

            $validateResponse = $this->actingAs($user)->postJson('/api/electricity/validate', [
                'meter_number' => $requestPayload['meter_number'],
                'disco_id'     => $requestPayload['disco_id'],
                'meter_type'   => $requestPayload['meter_type'],
            ]);

            $validateResponse->assertStatus(200)->assertJsonStructure([
               'status',
               'response' => ['name', 'address'],
               'message'
            ]);

            $validatedData = $validateResponse->json('response');
           
            $purchaseResponse = $this->actingAs($user)->postJson('/api/electricity/create', array_merge($requestPayload, [
                'owner_name'    => $validatedData['name'],
                'owner_address' => $validatedData['address'],
            ]));

            $purchaseResponse->assertStatus(200);

        } catch (\Exception $e) {
            Log::error("electricity purchase is successful when user has sufficient balance",  [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /** @test electricity purchase returns valid details */
    public function electricity_purchase_returns_valid_details()
    {
        try {
            $user = User::factory()->create([
                'account_balance' => 1000,
            ]);

            // Set up test data
            $requestPayload = [
                'amount'        => 500,
                'meter_number'  => '1111111111111',
                'disco_id'      => 'ikeja-electric',
                'meter_type'    => '1',
                'phone_number'  => '08130974397',
            ];

            $validateResponse = $this->actingAs($user)->postJson('/api/electricity/validate', [
                'meter_number' => $requestPayload['meter_number'],
                'disco_id'     => $requestPayload['disco_id'],
                'meter_type'   => $requestPayload['meter_type'],
            ]);

            $validateResponse->assertStatus(200)->assertJsonStructure([
               'status',
               'response' => ['name', 'address'],
               'message'
            ]);

            $validatedData = $validateResponse->json('response');
           
            $purchaseResponse = $this->actingAs($user)->postJson('/api/electricity/create', array_merge($requestPayload, [
                'owner_name'    => $validatedData['name'],
                'owner_address' => $validatedData['address'],
            ]));

            $purchaseResponse->assertStatus(200)->assertJson([
                'status' => true,
                'response' => [],
            ]);

        } catch (\Exception $e) {
            Log::error("electricity purchase is successful when user has sufficient balance",  [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
