<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlacklistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $types = ['phone', 'email', 'nin', 'bvn'];

        $blacklistData = collect(range(1, 10))->map(function () use ($types) {
            $type = $types[array_rand($types)];

            return [
                'type' => $type,
                'value' => match ($type) {
                    'phone' => '234' . rand(7000000000, 7999999999),
                    'email' => Str::random(10) . '@example.com',
                    'nin' => Str::random(11),
                    'bvn' => rand(10000000000, 99999999999),
                },
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        DB::table('blacklists')->insert($blacklistData);
    }
}
