<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            Schema::table($tableName, function (Blueprint $table) {
                $table->decimal('balance_after_refund', 20, 2)->after('balance_after')->nullable();
            });
        });
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
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('balance_after_refund');
            });
        });
    }
};
