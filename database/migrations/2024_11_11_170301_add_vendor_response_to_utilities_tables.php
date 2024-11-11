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
        $tables = collect([
            'airtime_transactions', 
            'cable_transactions', 
            'data_transactions', 
            'electricity_transactions', 
            'result_checker_transactions'
        ]);

        $tables->each(function ($tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'vendor_response')) {
                    $table->dropColumn('vendor_response');
                }
            });
            Schema::table($tableName, function (Blueprint $table) {
                $table->enum('vendor_response', ['successful', 'processing', 'pending', 'failed', 'refunded'])->after('status')->nullable();
            });
        });

        $this->run_vendor_response_query();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = collect([
            'airtime_transactions', 
            'cable_transactions', 
            'data_transactions', 
            'electricity_transactions', 
            'result_checker_transactions'
        ]);

        $tables->each(function ($tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'vendor_response')) {
                    $table->dropColumn('vendor_response');
                }
            });
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
            ->where('status', 0)
            ->update(['vendor_response' => 'failed']);

            DB::table($table) 
            ->where('status', 2)
            ->update(['vendor_response' => 'refunded']);

            DB::table($table)
            ->where('status', 1)
            ->update(['vendor_response' => 'successful']);
        }); 
    }
};
