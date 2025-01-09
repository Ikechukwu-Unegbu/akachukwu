<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class LoginApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
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


    /** @test test login succeeds with valid credentials */
    public function test_login_succeeds_with_valid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'login' => $this->user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email']]);
    }

    /** @test test login fails with incorrect credentials */
    public function test_login_fails_with_incorrect_credentials()
    {
        $response = $this->postJson('/api/login', [
            'login' => $this->user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Unauthorized']);
    }
   
}
