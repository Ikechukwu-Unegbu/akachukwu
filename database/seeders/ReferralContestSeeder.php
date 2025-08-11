<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReferralContest;
use Carbon\Carbon;

class ReferralContestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure this only runs locally
        if (!app()->environment('local')) {
            return;
        }

        $adminId = 1; // Change this to an actual admin user ID

        // Past contests (last year)
        ReferralContest::create([
            'start_date' => Carbon::now()->subYear()->startOfMonth(),
            'end_date' => Carbon::now()->subYear()->startOfMonth()->addDays(20),
            'active' => false,
            'created_by' => $adminId,
        ]);

        ReferralContest::create([
            'start_date' => Carbon::now()->subYear()->addMonths(2)->startOfMonth(),
            'end_date' => Carbon::now()->subYear()->addMonths(2)->startOfMonth()->addDays(15),
            'active' => false,
            'created_by' => $adminId,
        ]);

        // Current contest
        ReferralContest::create([
            'start_date' => Carbon::now()->startOfMonth(),
            'end_date' => Carbon::now()->endOfMonth(),
            'active' => true,
            'created_by' => $adminId,
        ]);

        // Upcoming contests
        ReferralContest::create([
            'start_date' => Carbon::now()->addMonth()->startOfMonth(),
            'end_date' => Carbon::now()->addMonth()->startOfMonth()->addDays(20),
            'active' => false,
            'created_by' => $adminId,
        ]);

        ReferralContest::create([
            'start_date' => Carbon::now()->addMonths(3)->startOfMonth(),
            'end_date' => Carbon::now()->addMonths(3)->startOfMonth()->addDays(20),
            'active' => false,
            'created_by' => $adminId,
        ]);
    }
}
