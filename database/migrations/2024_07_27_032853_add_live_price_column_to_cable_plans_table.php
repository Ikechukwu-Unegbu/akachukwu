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
        Schema::table('cable_plans', function (Blueprint $table) {
            $table->decimal('live_amount', 20, 2)->after('amount')->nullable();
            $table->string('live_package')->after('live_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cable_plans', function (Blueprint $table) {
            $table->dropColumn('live_amount');
            $table->dropColumn('live_package');
        });
    }
};
