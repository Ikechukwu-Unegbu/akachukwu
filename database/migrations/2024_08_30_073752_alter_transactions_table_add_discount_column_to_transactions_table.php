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
        Schema::table('airtime_transactions', function (Blueprint $table) {
            $table->integer('discount')->after('amount');
        });

        Schema::table('cable_transactions', function (Blueprint $table) {
            $table->integer('discount')->after('amount');
        });

        Schema::table('data_transactions', function (Blueprint $table) {
            $table->integer('discount')->after('plan_amount');
        });

        Schema::table('electricity_transactions', function (Blueprint $table) {
            $table->integer('discount')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('airtime_transactions', function (Blueprint $table) {
            $table->dropColumn('discount');
        });

        Schema::table('cable_transactions', function (Blueprint $table) {
            $table->dropColumn('discount');
        });

        Schema::table('data_transactions', function (Blueprint $table) {
            $table->dropColumn('discount');
        });

        Schema::table('electricity_transactions', function (Blueprint $table) {
            $table->dropColumn('discount');
        });
    }
};
