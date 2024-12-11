<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_endpoint_returns_correct_structure_for_valid_username()
    {
        // Create a user using the User factory
        $user = \App\Models\User::factory()->create([
            'username' => 'Viviane',
            'email' => 'vivianehealthlab@gmail.com',
            'phone' => '08034133376',
            'password' => bcrypt('password'),
            // You can add any other necessary user attributes
        ]);

        // Make a GET request to the endpoint using the created user's username
        $response = $this->getJson("api/user/{$user->username}");

        // Assert that the response status is 200 (OK)
        $response->assertStatus(200);

        // Assert the response structure
        $response->assertJsonStructure([
            'status',
            'response' => [
                'id',
                'name',
                'username',
                'email',
                'role',
                'user_level',
                'email_verified_at',
                'image',
                'address',
                'mobile',
                'referer_username',
                'gender',
                'account_balance',
                'wallet_balance',
                'bonus_balance',
                'bvn',
                'nin',
                'created_at',
                'updated_at',
                'phone',
                'deactivation',
                'blocked_by_admin',
                'admin_block_reason',
                'deleted_at',
                'soft_deleted_by',
                'blocked_by',
            ],
            'message',
        ]);
    }


}
