<?php

namespace Database\Factories\Education;

use App\Models\Data\DataVendor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Education\ResultCheckerTransaction>
 */
class ResultCheckerTransactionFactory extends Factory
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
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'api_data_id' => $this->faker->uuid,
            'api_response' => json_encode([
                'status' => $this->faker->randomElement(['success', 'error']),
                'message' => $this->faker->sentence,
                'data' => [
                    'reference_id' => $this->faker->uuid,
                    'exam_name' => $this->faker->word,
                    'quantity' => $this->faker->numberBetween(1, 10),
                ],
            ]),
            'balance_before' => $this->faker->randomFloat(2, 0, 1000),
            'balance_after' => $this->faker->randomFloat(2, 0, 1000),
            'exam_name' => $this->faker->word,
            'quantity' => $this->faker->numberBetween(1, 10),
            'reference_id' => $this->faker->uuid,
            'result_checker_id' => $this->faker->uuid,
            'status' => $this->faker->randomElement([1, 0]),
        ];
    }
}
