<?php

namespace Database\Factories;

use App\Helpers\GeneralHelpers;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'uuid'=>GeneralHelpers::generateUniqueUuid('announcements'),
            'title' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
            'type' => $this->faker->randomElement(['info', 'warning', 'success', 'error']),
            'start_at' => Carbon::now()->addDays($this->faker->numberBetween(0, 10)),
            'end_at' => Carbon::now()->addDays($this->faker->numberBetween(11, 20)),
            'is_active' => $this->faker->boolean(80), // 80% chance of being true
        ];
      
    }
}
