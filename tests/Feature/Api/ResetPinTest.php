<?php

use App\Models\Otp;
use Tests\TestCase;
use App\Models\User;
use App\Services\Account\UserPinService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResetPinTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $pin = 1122;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);
    }

    public function test_create_pin_fails_with_missing_pin()
    {
        $response = $this->actingAs($this->user)->postJson('/api/pin/create', [
            'pin_confirmation' => $this->pin,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['pin'])
            ->assertJson([
                'errors' => [
                    'pin' => ['The pin field is required.']
                ]
            ]);
    }

    public function test_create_pin_fails_with_missing_pin_confirmation()
    {
        $response = $this->actingAs($this->user)->postJson('/api/pin/create', [
            'pin' => $this->pin,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['pin_confirmation'])
            ->assertJson([
                'errors' => [
                    'pin_confirmation' => ['The pin confirmation field is required.']
                ]
            ]);
    }

    public function test_create_new_pin()
    {
        $response = $this->actingAs($this->user)->postJson('/api/pin/create', [
            'pin' => $this->pin,
            'pin_confirmation' => $this->pin,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status'   =>    true,
                'error'    =>    NULL,
                'message'  =>    "PIN created successfully.",
            ]);
    }

    public function test_reset_pin_fails_with_missing_pin()
    {
        $response = $this->actingAs($this->user)->postJson('/api/pin/reset-aux', [
            'confirm_pin' => '1234',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['pin'])
                ->assertJson([
                    'errors' => [
                        'pin' => ['The pin field is required.']
                    ]
                ]);
    }

    public function test_reset_pin_fails_with_missing_current_pin()
    {
        $response = $this->actingAs($this->user)->postJson('/api/pin/reset-aux', [
            'pin' => (string) $this->pin
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['confirm_pin'])
                ->assertJson([
                    'errors' => [
                        'confirm_pin' => ['The confirm pin field is required.']
                    ]
                ]);
    }

    public function test_reset_pin_fails_with_current_pin_does_not_match_pin()
    {
        $response = $this->actingAs($this->user)->postJson('/api/pin/reset-aux', [
            'pin' => (string) $this->pin,
            'confirm_pin' => '1234',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['confirm_pin'])
                ->assertJson([
                    'errors' => [
                        'confirm_pin' => ['The confirm pin field must match pin.']
                    ]
                ]);
    }

    public function test_reset_pin_succeeds()
    {
        $response = $this->actingAs($this->user)->postJson('/api/pin/reset-aux', [
            'pin' => (string) $this->pin,
            'confirm_pin' => (string) $this->pin,
        ]);

        $response->assertStatus(200);
    }

    // /**
    //  * Test that reset fails with missing new PIN.
    //  */
    // public function test_reset_pin_fails_with_missing_pin()
    // {
    //     $user = User::factory()->create(['email' => 'user@example.com']);
    //     Otp::factory()->create(['email' => $user->email, 'otp' => '123456']);

    //     $response = $this->postJson('/api/reset-pin', [
    //         'email' => $user->email,
    //         'otp' => '123456',
    //     ]);

    //     $response->assertStatus(422)
    //              ->assertJsonValidationErrors(['new_pin']);
    // }
}
