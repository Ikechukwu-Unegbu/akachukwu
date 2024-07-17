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
        Schema::table('data_networks', function (Blueprint $table) {
            $table->string('network_id')->change();
        });

        Schema::table('data_types', function (Blueprint $table) {
            $table->string('network_id')->change();
        });

        Schema::table('data_plans', function (Blueprint $table) {
            $table->string('network_id')->change();
            $table->string('data_id')->change();
            $table->string('type_id')->change();
        });

        Schema::table('cables', function (Blueprint $table) {
            $table->string('cable_id')->change();
        });

        Schema::table('cable_plans', function (Blueprint $table) {
            $table->string('cable_plan_id')->change();
        });

        Schema::table('electric', function (Blueprint $table) {
            $table->string('disco_id')->change();
        });

        Schema::table('electricity_transactions', function (Blueprint $table) {
            $table->string('disco_id')->change();
        });

        Schema::table('cable_transactions', function (Blueprint $table) {
            $table->string('cable_id')->change();
            $table->string('cable_plan_id')->change();
        });

        Schema::table('data_transactions', function (Blueprint $table) {
            $table->string('network_id')->change();
            $table->string('data_id')->change();
            $table->string('type_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('data_networks', function (Blueprint $table) {
        //     $table->integer('network_id')->change();
        // });

        // Schema::table('data_types', function (Blueprint $table) {
        //     $table->integer('network_id')->change();
        // });

        // Schema::table('data_plans', function (Blueprint $table) {
        //     $table->integer('network_id')->change();
        //     $table->integer('data_id')->change();
        //     $table->integer('type_id')->change();
        // });

        // Schema::table('cables', function (Blueprint $table) {
        //     $table->integer('cable_id')->change();
        // });

        // Schema::table('cable_plans', function (Blueprint $table) {
        //     $table->integer('cable_plan_id')->change();
        // });

        // Schema::table('electric', function (Blueprint $table) {
        //     $table->integer('disco_id')->change();
        // });

        // Schema::table('electricity_transactions', function (Blueprint $table) {
        //     $table->integer('disco_id')->change();
        // });

        // Schema::table('cable_transactions', function (Blueprint $table) {
        //     $table->integer('cable_id')->change();
        //     $table->integer('cable_plan_id')->change();
        // });

        // Schema::table('data_transactions', function (Blueprint $table) {
        //     $table->integer('network_id')->change();
        //     $table->integer('data_id')->change();
        //     $table->integer('type_id')->change();
        // });
    }
};
