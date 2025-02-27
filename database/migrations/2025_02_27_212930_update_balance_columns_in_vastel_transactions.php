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
            $table->decimal('balance_before', 10, 2)->nullable()->change();
            $table->decimal('balance_after', 10, 2)->nullable()->change();
        });
     
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vastel_transactions', function (Blueprint $table) {
            $table->decimal('balance_before')->nullable()->change();
            $table->decimal('balance_after')->nullable()->change();
        });
    }
};
