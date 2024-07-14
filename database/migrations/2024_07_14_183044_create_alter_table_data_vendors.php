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
        Schema::table('data_vendors', function (Blueprint $table) {
            $table->string('public_key')->after('token')->nullable();
            $table->string('secret_key')->after('public_key')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_vendors', function (Blueprint $table) {
            $table->dropColumn('public_key');
            $table->dropColumn('secret_key');
        });
    }
};
