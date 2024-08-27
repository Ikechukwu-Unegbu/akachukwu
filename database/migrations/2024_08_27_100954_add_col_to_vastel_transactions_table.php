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
        Schema::table('vastel_transactions', function (Blueprint $table) {
            $table->string('description')->nullable();
            $table->decimal('balance_before')->nullable();
            $table->decimal('balance_after')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vastel_transactions', function (Blueprint $table) {
            $table->dropColumn(['description', 'balance_before', 'balance_after']);
        });
    }
};
