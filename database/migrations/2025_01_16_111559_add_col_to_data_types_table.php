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
        Schema::table('data_types', function (Blueprint $table) {
            $table->decimal('referral_pay')->default(0);//perctage
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_types', function (Blueprint $table) {
            $table->dropColumn(['referral_pay']);
        });
    }
};
