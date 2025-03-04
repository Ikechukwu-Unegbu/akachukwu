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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->decimal('transfer_charges', 20, 2)->default(50);
            $table->decimal('minimum_transfer', 20, 2)->default(100);
            $table->decimal('maximum_transfer', 20, 2)->default(30000);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('transfer_charges');
            $table->dropColumn('minimum_transfer');
            $table->dropColumn('maximum_transfer');
        });
    }
};
