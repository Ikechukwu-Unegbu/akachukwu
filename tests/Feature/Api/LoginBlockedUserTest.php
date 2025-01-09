<?php

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginBlockedUserTest extends TestCase
{
    use RefreshDatabase;

    protected $blockedUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->blockedUser = User::factory()->create([
            'email' => 'blockeduser@example.com',
            'password' => bcrypt('password123'),
            'blocked_by_admin' => true
        ]);
    }

    /** @test test login fails when email is missing */
    public function test_login_fails_when_email_is_missing()
    {
        $response = $this->postJson('/api/login', [
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['login']);
    }

    /** @test test login fails when password is missing */
    public function test_login_fails_when_password_is_missing()
    {
        $response = $this->postJson('/api/login', [
            'login' => 'user@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /** @test test blocked user cannot login */
    public function test_blocked_user_cannot_login()
    {
        $response = $this->postJson('/api/login', [
            'login' => $this->blockedUser->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'message' => 'Account Currently Inaccessible. Please contact Support for further assistance.',
            ]);
    }
}
