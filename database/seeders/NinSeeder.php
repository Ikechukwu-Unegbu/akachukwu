<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [];

        for ($i = 1; $i <= 20; $i++) {
            $data[] = [
                'value' => Str::random(10),
                'data' => json_encode(['info' => 'Sample Data ' . $i]),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('nins')->insert($data);
    }
}
