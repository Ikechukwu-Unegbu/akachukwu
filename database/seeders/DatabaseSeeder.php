<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\Data\DataNetworkSeeder;
use Database\Seeders\Data\DataPlanSeeder;
use Database\Seeders\Data\DataTypeSeeder;
use Database\Seeders\Data\DataVendorSeeder;
use Database\Seeders\Data\VTPassSeeder;
use Database\Seeders\Utility\CablePlanSeeder;
use Database\Seeders\Utility\CableSeeder;
use Database\Seeders\Utility\ElectricitySeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            PermissionSeeder::class,
            PaymentGatewaySeeder::class,
            DataVendorSeeder::class,
            DataNetworkSeeder::class,
            DataTypeSeeder::class,
            DataPlanSeeder::class,
            ElectricitySeeder::class,
            CableSeeder::class,
            CablePlanSeeder::class,
            VTPassSeeder::class,
        ]);
    }
}
