<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Data\DataType;
use App\Helpers\GeneralHelpers;
use App\Services\VendorTestService;
use App\Models\VendorServiceMapping;
use Database\Seeders\Data\VTPassSeeder;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\Data\DataPlanSeeder;
use Database\Seeders\Data\DataTypeSeeder;
use Database\Seeders\Data\DataVendorSeeder;
use Database\Seeders\Data\DataNetworkSeeder;
use Database\Seeders\VendorServiceMappingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DataReferralTest extends TestCase
{
    use RefreshDatabase;

    protected VendorTestService $vendorService;

    protected const ACCOUNT_BALANCE = 1000;
    protected const NIN = '12345678900';
    
    protected User $referrer;
    protected User $referredUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedDatabase();
        $this->initializeVendorService();
        $this->initializeUsers();    
    }

    private function initializeUsers(): void
    {
        $this->referrer = User::factory()->create([
            'username' => 'unique_user_a',
            'email' => 'unique_user_a@example.com',
        ]);

        $this->referredUser = User::factory()->create([
            'username' => 'unique_user_b',
            'email' => 'unique_user_b@example.com',
            'account_balance' => self::ACCOUNT_BALANCE,
            'nin' => self::NIN,
        ]);
    }

    private function seedDatabase(): void
    {
        Artisan::call('db:seed', ['--class' => DataVendorSeeder::class]);
        Artisan::call('db:seed', ['--class' => VendorServiceMappingSeeder::class]);
        Artisan::call('db:seed', ['--class' => DataNetworkSeeder::class]);
        Artisan::call('db:seed', ['--class' => DataTypeSeeder::class]);
        Artisan::call('db:seed', ['--class' => DataPlanSeeder::class]);
        Artisan::call('db:seed', ['--class' => VTPassSeeder::class]);
        DataType::query()->update(['referral_pay' => 10]);        
    }

    private function initializeVendorService(): void
    {
        $this->vendorService = new VendorTestService();
        VendorServiceMapping::query()->update(['vendor_id' => $this->vendorService->getVendor()->id]);
    }

    private function applyReferralCode(string $referralCode, User $user): void
    {
        $request = request()->merge(['referral_code' => $referralCode]);
        GeneralHelpers::checkReferrer($request, $user);
    }
   
    /** @test */
    public function it_creates_data_with_valid_referral()
    {
        $this->applyReferralCode($this->referrer->username, clone $this->referredUser);
        $this->assertIsObject($this->referredUser->referralsReceived);

        $response = $this->actingAs($this->referredUser)->postJson('/api/data/create', [
            'network_id' => $this->vendorService->getNetworkId(),
            'data_type_id' => $this->vendorService->getDataTypeId(),
            'plan_id' => $this->vendorService->getPlanId(),
            'phone_number' => $this->vendorService->getPhoneNumber(),
        ]);

        $response->assertStatus(200);
    }
}
