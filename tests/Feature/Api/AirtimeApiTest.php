<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\VendorServiceMapping;
use Database\Seeders\Data\DataNetworkSeeder;
use Illuminate\Support\Facades\Log;
use Database\Seeders\Data\VTPassSeeder;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\Data\DataVendorSeeder;
use Database\Seeders\SiteSettingSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Seeders\VendorServiceMappingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\VendorTestService;

class AirtimeApiTest extends TestCase
{
    use RefreshDatabase;

    protected $vendorService;

    protected CONST ACCOUNT_BALANCE = 100;

    protected function setUp(): void
    {
        parent::setUp();        
        Artisan::call('db:seed', ['--class' => DataVendorSeeder::class]);
        Artisan::call('db:seed', ['--class' => VendorServiceMappingSeeder::class]);
        Artisan::call('db:seed', ['--class' => DataNetworkSeeder::class]);
        Artisan::call('db:seed', ['--class' => VTPassSeeder::class]);
        Artisan::call('db:seed', ['--class'=>SiteSettingSeeder::class]);

        // $this->vendorService = new VendorTestService('POSTRANET');
        // $this->vendorService = new VendorTestService('GLADTIDINGSDATA');
        $this->vendorService = new VendorTestService();
        VendorServiceMapping::query()->update(['vendor_id' => $this->vendorService->getVendor()->id]);
    }

    /** @test airtime purchase fails when user has insufficient balance */
    public function airtime_purchase_fails_when_user_has_insufficient_balance()
    {
        try {
            $user = User::factory()->create([
                'account_balance' => 10,
            ]);
    
            $response = $this->actingAs($user)->postJson('/api/airtime/create', [
                'network_id' => $this->vendorService->getNetworkId(),
                'amount'    => $this->vendorService->getAmount(),
                'phone_number' => $this->vendorService->getPhoneNumber(),
            ]);
    
            $response->assertStatus(422);
        } catch (\Exception $e) {
            Log::error("airtime purchase fails when user has insufficient balance",  [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /** @test airtime purchase is successful when user has sufficient balance */
    public function airtime_purchase_is_successful_when_user_has_sufficient_balance()
    {
        try {
            $user = User::factory()->create([
                'account_balance' => self::ACCOUNT_BALANCE,
            ]);
    
            $response = $this->actingAs($user)->postJson('/api/airtime/create', [
                'network_id' => $this->vendorService->getNetworkId(),
                'amount'    => $this->vendorService->getAmount(),
                'phone_number' => $this->vendorService->getPhoneNumber(),
            ]);
    
            $response->assertStatus(200);

        } catch (\Exception $e) {
            Log::error("airtime purchase is successful when user has sufficient balance",  [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /** @test airtime purchase returns valid details */
    public function airtime_purchase_returns_valid_details()
    {
        try {
            $user = User::factory()->create([
                'account_balance' => self::ACCOUNT_BALANCE,
            ]);
    
            $response = $this->actingAs($user)->postJson('/api/airtime/create', [
                'network_id' => $this->vendorService->getNetworkId(),
                'amount'    => $this->vendorService->getAmount(),
                'phone_number' => $this->vendorService->getPhoneNumber(),
            ]);

            $response->assertStatus(200);

            $response->assertJsonStructure([
                'status',
                'response',
                'message',
            ]);
        } catch (\Exception $e) {
            Log::error("airtime purchase returns valid details",  [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
