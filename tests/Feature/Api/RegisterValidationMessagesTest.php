<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterValidationMessagesTest extends TestCase
{
    use RefreshDatabase;

    /** @test registration returns message for missing name */
    public function test_registration_returns_message_for_missing_name()
    {
        $response = $this->postJson('/api/register', [
            'email' => 'user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name'])
                 ->assertJson([
                     'errors' => [
                         'name' => ['The name field is required.'],
                     ]
                 ]);
    }

    /** @test registration returns message for missing email */
    public function test_registration_returns_message_for_missing_email()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email'])
                 ->assertJson([
                     'errors' => [
                         'email' => ['The email field is required.'],
                     ]
                 ]);
    }

    /** @test registration returns message for missing username */
    public function test_registration_returns_message_for_missing_username()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
                    ->assertJsonValidationErrors(['username'])
                    ->assertJson([
                        'errors' => [
                            'username' => ['The username field is required.'],
                        ]
                    ]);
    }

    /** @test registration returns message for missing phne */
    public function test_registration_returns_message_for_missing_phne()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
                    ->assertJsonValidationErrors(['phone'])
                    ->assertJson([
                        'errors' => [
                            'phone' => ['The phone field is required.'],
                        ]
                    ]);
    }

    /** @test registration returns message for invalid email */
    public function test_registration_returns_message_for_invalid_email()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email'])
                 ->assertJson([
                     'errors' => [
                         'email' => ['The email field must be a valid email address.'],
                     ]
                 ]);
    }

    /** @test registration_returns_message_for_password_mismatch */
    public function test_registration_returns_message_for_password_mismatch()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password'])
                 ->assertJson([
                     'errors' => [
                         'password' => ['The password field confirmation does not match.'],
                     ]
                 ]);
    }
}
