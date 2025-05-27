<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->json('bank_config')->nullable();
        });

        // Check if bank with code 50515 exists
        $bank = DB::table('banks')->where('code', '50515')->first();

        if (!$bank) {
            // Create the bank if it doesn't exist
            $bankId = DB::table('banks')->insertGetId([
                'name' => 'MONIEPOINT',
                'code' => '50515',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $bankId = $bank->id;
        }

        // Prepare the config with the bank id in monnify
        $bankConfig = [
            "default" => [
                "palm_pay" => true
            ],
            "monnify" => [$bankId]
        ];

        DB::table('site_settings')->update([
            'bank_config' => json_encode($bankConfig)
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('bank_config');
        });
    }
};
