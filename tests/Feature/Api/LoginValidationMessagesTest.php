<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginValidationMessagesTest extends TestCase
{
    use RefreshDatabase;

    /** @test login returns message for missing email */
    public function test_login_returns_message_for_missing_email()
    {
        $response = $this->postJson('/api/login', [
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['login'])
                 ->assertJson([
                     'errors' => [
                         'login' => ['Username or email field is empty'],
                     ]
                 ]);
    }

    /** @test login returns message for missing password */
    public function test_login_returns_message_for_missing_password()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password'])
                 ->assertJson([
                     'errors' => [
                         'password' => ['Password field is required'],
                     ]
                 ]);
    }
}
