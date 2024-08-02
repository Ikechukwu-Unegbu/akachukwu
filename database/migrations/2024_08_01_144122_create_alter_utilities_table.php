<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("UPDATE `airtime_transactions` SET `api_response` = '{}' WHERE JSON_VALID(`api_response`) = 0");
        DB::statement("UPDATE `cable_transactions` SET `api_response` = '{}' WHERE JSON_VALID(`api_response`) = 0");
        DB::statement("UPDATE `data_transactions` SET `api_response` = '{}' WHERE JSON_VALID(`api_response`) = 0");
        DB::statement("UPDATE `electricity_transactions` SET `api_response` = '{}' WHERE JSON_VALID(`api_response`) = 0");
        DB::statement("UPDATE `result_checker_transactions` SET `api_response` = '{}' WHERE JSON_VALID(`api_response`) = 0");

        Schema::table('airtime_transactions', function (Blueprint $table) {
            $table->json('api_response')->nullable()->change();
        });

        Schema::table('cable_transactions', function (Blueprint $table) {
            $table->json('api_response')->nullable()->change();
        });

        Schema::table('data_transactions', function (Blueprint $table) {
            $table->json('api_response')->nullable()->change();
        });

        Schema::table('electricity_transactions', function (Blueprint $table) {
            $table->json('api_response')->nullable()->change();
        });

        Schema::table('result_checker_transactions', function (Blueprint $table) {
            $table->json('api_response')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('airtime_transactions', function (Blueprint $table) {
            $table->text('api_response')->nullable()->change();
        });

        Schema::table('cable_transactions', function (Blueprint $table) {
            $table->text('api_response')->nullable()->change();
        });

        Schema::table('data_transactions', function (Blueprint $table) {
            $table->text('api_response')->nullable()->change();
        });

        Schema::table('electricity_transactions', function (Blueprint $table) {
            $table->text('api_response')->nullable()->change();
        });

        Schema::table('result_checker_transactions', function (Blueprint $table) {
            $table->text('api_response')->nullable()->change();
        });
    }
};
