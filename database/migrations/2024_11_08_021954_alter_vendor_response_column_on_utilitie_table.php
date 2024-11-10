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
        collect([
            'airtime_transactions', 
            'cable_transactions', 
            'data_transactions', 
            'electricity_transactions', 
            'result_checker_transactions'
        ])->each(function ($table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('vendor_response');
            });

            Schema::table($table, function (Blueprint $table) {
                $table->enum('vendor_status', ['successful', 'processing', 'pending', 'failed', 'refunded'])->after('status')->nullable();
            });
        });

        $this->run_vendor_status_query();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        collect([
            'airtime_transactions', 
            'cable_transactions', 
            'data_transactions', 
            'electricity_transactions', 
            'result_checker_transactions'
        ])->each(function ($table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('vendor_status');
            });
        });
    }

    public function run_vendor_status_query()
    {
        collect([
            'airtime_transactions', 
            'cable_transactions', 
            'data_transactions', 
            'electricity_transactions', 
            'result_checker_transactions'
        ])->each(function ($table) {
            DB::table($table) 
            ->where('status', 0)
            ->update(['vendor_status' => 'failed']);

            DB::table($table) 
            ->where('status', 2)
            ->update(['vendor_status' => 'refunded']);

            DB::table($table)
            ->where('status', 1)
            ->update(['vendor_status' => 'successful']);
        }); 
    }
};
