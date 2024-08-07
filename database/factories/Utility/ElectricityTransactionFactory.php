<?php

namespace Database\Factories\Utility;

use App\Models\Data\DataVendor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Utility\ElectricityTransaction>
 */
class ElectricityTransactionFactory extends Factory
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
            'disco_id' => $this->faker->uuid,
            'disco_name' => $this->faker->company,
            'meter_number' => $this->faker->numerify('MTR#######'),
            'meter_type_id' => $this->faker->word,
            'meter_type_name' => $this->faker->word,
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'customer_mobile_number' => $this->faker->phoneNumber,
            'customer_name' => $this->faker->name,
            'customer_address' => $this->faker->address,
            'balance_before' => $this->faker->randomFloat(2, 0, 1000),
            'balance_after' => $this->faker->randomFloat(2, 0, 1000),
            'token' => $this->faker->uuid,
            'api_data_id' => $this->faker->uuid,
            // 'api_response' => $this->faker->text,
            'api_response' => json_encode([
                'status' => $this->faker->randomElement(['success', 'error']),
                'message' => $this->faker->sentence,
                'data' => [
                    'transaction_id' => $this->faker->uuid,
                    'amount' => $this->faker->randomFloat(2, 100, 1000),
                    'disco_name' => $this->faker->company,
                    'meter_number' => $this->faker->numerify('MTR#######'),
                ],
            ]),
            'status' => $this->faker->randomElement([0, 1]),
        ];
    }
}
