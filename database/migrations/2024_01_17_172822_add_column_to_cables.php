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
        Schema::table('cables', function (Blueprint $table) {
            $table->integer('vendor_id')->after('id');
            $table->integer('cable_id')->after('vendor_id');
            $table->string('cable_name')->after('cable_id');
            $table->tinyInteger('status')->after('cable_name')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cables', function (Blueprint $table) {
            $table->dropColumn('vendor_id');
            $table->dropColumn('cable_id');
            $table->dropColumn('cable_name');
            $table->dropColumn('status');
        });
    }
};
