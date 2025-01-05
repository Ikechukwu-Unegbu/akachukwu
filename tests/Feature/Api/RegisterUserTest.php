<?php
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test that a user can register when all required fields are provided. */
    public function test_user_can_register_with_valid_information()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'username' => 'janedoe',
            'email' => 'john.doe@example.com',
            'phone' => '081111111',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'status',
                    'user' => ['id', 'email', 'username']
                ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
        ]);
    }
}
