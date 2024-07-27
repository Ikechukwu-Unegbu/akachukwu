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
        Schema::table('result_checkers', function (Blueprint $table) {
            $table->decimal('live_amount', 20, 2)->after('amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('result_checkers', function (Blueprint $table) {
            $table->dropColumn('live_amount');
        });
    }
};
