<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MoneyTransferApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test money transfer get to intended user */
    public function money_transfer_get_to_intended_user()
    {
        $sender = User::factory()->create([
            'account_balance' => 100,
        ]);

        $receiver = User::factory()->create([
            'account_balance' => 0,
        ]);

        $response = $this->actingAs($sender)->postJson('/api/transfer', [
            'recipient'=> $receiver->username, 
            'amount'=> 50, 
            'type'=> 'vastel'
        ]);

        $response->assertStatus(200)->assertJson([
            'status' => true,
            'response' => []
        ]);
    }

    /** @test user cannot transfer more than they have */
    public function user_cannot_transfer_more_than_they_have()
    {
        $sender = User::factory()->create([
            'account_balance' => 100,
        ]);

        $receiver = User::factory()->create([
            'account_balance' => 0,
        ]);

        $response = $this->actingAs($sender)->postJson('/api/transfer', [
            'recipient'=> $receiver->username, 
            'amount'=> 500, 
            'type'=> 'vastel'
        ]);

        $response->assertJson([
            'status' => false,
            'message' => 'Insufficient wallet balance. Please top up your account to complete the transfer.'
        ]);
    }
}
