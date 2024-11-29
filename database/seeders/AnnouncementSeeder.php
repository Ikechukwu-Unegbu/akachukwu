<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Announcement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $announcements = [];

        for ($i = 0; $i < 5; $i++) {
            $announcements[] = [
                'uuid' => Str::uuid(),
                'title' => fake()->sentence,
                'message' => fake()->paragraph,
                'type' => fake()->randomElement(['info', 'warning', 'success', 'error']),
                'start_at' => fake()->optional()->dateTimeBetween('-1 month', '+1 month'),
                'end_at' => fake()->optional()->dateTimeBetween('now', '+2 months'),
                'is_active' => fake()->boolean,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        Announcement::insert($announcements);
    }
}
