<?php

namespace Database\Factories\Utility;

use App\Models\Data\DataVendor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Utility\CableTransaction>
 */
class CableTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dataVendorArrays = DataVendor::pluck('id')->toArray();
        $userIdArry = User::pluck('id')->toArray();
        return [
            'transaction_id' => $this->generateUniqueId(),
            'user_id' => $this->faker->randomElement($userIdArry), // Assumes a UserFactory exists
            'vendor_id' => $this->faker->randomElement($dataVendorArrays), // Assumes a DataVendorFactory exists
            'cable_name' => $this->faker->word,
            'cable_id' => $this->faker->uuid,
            'cable_plan_name' => $this->faker->word,
            'cable_plan_id' => $this->faker->uuid,
            'smart_card_number' => $this->faker->numerify('SCN#######'),
            'customer_name' => $this->faker->name,
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'balance_before' => $this->faker->randomFloat(2, 0, 1000),
            'balance_after' => $this->faker->randomFloat(2, 0, 1000),
            'api_data_id' => $this->faker->uuid,
            // 'api_response' => $this->faker->text,
            'api_response' => json_encode([
                'status' => $this->faker->randomElement(['success', 'error']),
                'message' => $this->faker->sentence,
                'data' => [
                    'cable_name' => $this->faker->word,
                    'cable_plan' => $this->faker->word,
                    'amount' => $this->faker->randomFloat(2, 100, 1000),
                ],
            ]),

            'status' => $this->faker->randomElement([0, 1]),
        ];
    }
}
