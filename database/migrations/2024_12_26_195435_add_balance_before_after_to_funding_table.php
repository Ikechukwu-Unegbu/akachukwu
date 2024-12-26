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
        collect([
            'flutterwave_transactions',
            'paystack_transactions',
            'pay_vessel_transactions',
            'monnify_transactions',
        ])->each(function ($tableName) {            
            Schema::table($tableName, function (Blueprint $table) {
                $table->decimal('balance_before', 20, 2)->after('status');
                $table->decimal('balance_after', 20, 2)->after('balance_before');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        collect([
            'flutterwave_transactions',
            'paystack_transactions',
            'pay_vessel_transactions',
            'monnify_transactions',
        ])->each(function ($tableName) {            
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('balance_before');
                $table->dropColumn('balance_after');
            });
        });
    }
};
