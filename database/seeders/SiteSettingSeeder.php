<?php

namespace Database\Seeders;

use App\Models\SiteSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (SiteSettings::count() === 0) {
            // Create one record in the site_settings table
            SiteSettings::create([
                'stie_title' => 'Your Site Title',
                'email' => 'admin@example.com',
                // Add other fields and their values
            ]);
        }
    }
}
