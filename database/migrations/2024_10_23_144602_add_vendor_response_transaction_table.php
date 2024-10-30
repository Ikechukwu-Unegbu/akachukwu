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
        Schema::table('airtime_transactions', function (Blueprint $table) {
            $table->enum('vendor_response', ['successful', 'processing', 'pending', 'failed'])->after('api_response')->nullable();
        });

        Schema::table('cable_transactions', function (Blueprint $table) {
            $table->enum('vendor_response', ['successful', 'processing', 'pending', 'failed'])->after('api_response')->nullable();
        });

        Schema::table('data_transactions', function (Blueprint $table) {
            $table->enum('vendor_response', ['successful', 'processing', 'pending', 'failed'])->after('api_response')->nullable();
        });

        Schema::table('electricity_transactions', function (Blueprint $table) {
            $table->enum('vendor_response', ['successful', 'processing', 'pending', 'failed'])->after('api_response')->nullable();
        });

        Schema::table('result_checker_transactions', function (Blueprint $table) {
            $table->enum('vendor_response', ['successful', 'processing', 'pending', 'failed'])->after('api_response')->nullable();
        });

        $this->run_vendor_response_query();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('airtime_transactions', function (Blueprint $table) {
            $table->dropColumn('vendor_response');
        });

        Schema::table('cable_transactions', function (Blueprint $table) {
            $table->dropColumn('vendor_response');
        });

        Schema::table('data_transactions', function (Blueprint $table) {
            $table->dropColumn('vendor_response');
        });

        Schema::table('electricity_transactions', function (Blueprint $table) {
            $table->dropColumn('vendor_response');
        });

        Schema::table('result_checker_transactions', function (Blueprint $table) {
            $table->dropColumn('vendor_response');
        });
    }

    public function run_vendor_response_query()
    {
        collect([
            'airtime_transactions', 
            'cable_transactions', 
            'data_transactions', 
            'electricity_transactions', 
            'result_checker_transactions'
        ])->each(function ($table) {
            DB::table($table) 
            ->whereIn('status', [0, 2])
            ->update(['vendor_response' => 'failed']);

            DB::table($table)
            ->where('status', 1)
            ->update(['vendor_response' => 'successful']);
        }); 
    }
};
