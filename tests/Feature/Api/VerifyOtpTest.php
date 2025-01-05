<?php

use App\Models\Otp;
use Tests\TestCase;
use App\Models\User;
use App\Services\OTPService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class VerifyOtpTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $otp;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->otp = (new OTPService())->generateOTP($this->user);
    }

    /** @test that OTP verification fails with incorrect OTP. */
    public function test_verify_otp_fails_with_incorrect_otp()
    {
        $response = $this->postJson('/api/verify-otp', [
            'email' => $this->user->email,
            'otp' => '0000x',
        ]);

        $response->assertStatus(403)
                    ->assertJson([
                        'message' => 'Invalid otp',
                    ]);
    }

    /** @test that OTP verification succeeds with correct OTP. */
    public function test_verify_otp_succeeds_with_correct_otp()
    {
        $response = $this->postJson('/api/verify-otp', [
            'email' => $this->user->email,
            'otp' => (string) $this->otp,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Valid Otp',
                 ]);
    }

    /** @test that OTP verification succeeds with correct OTP. */
    public function test_resend_otp_succeeds()
    {
        $response = $this->postJson('/api/resend-otp', [
            'email' => $this->user->email
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                    'status'=>'success'
                 ]);
    }
}
