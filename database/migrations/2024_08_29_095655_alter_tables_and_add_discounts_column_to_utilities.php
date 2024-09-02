<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_networks', function (Blueprint $table) {
            $table->integer('airtime_discount')->after('name');
            $table->integer('data_discount')->after('airtime_discount');
        });

        Schema::table('cables', function (Blueprint $table) {
            $table->integer('discount')->after('cable_name');
        });

        Schema::table('electric', function (Blueprint $table) {
            $table->integer('discount')->after('disco_name');
        });
    }

    public function down(): void
    {
        Schema::table('data_networks', function (Blueprint $table) {
            $table->dropColumn('airtime_discount');
            $table->dropColumn('data_discount');
        });

        Schema::table('cables', function (Blueprint $table) {
            $table->dropColumn('discount');
        });

        Schema::table('electric', function (Blueprint $table) {
            $table->dropColumn('discount');
        });
    }
};
