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
            $table->string('mtn_logo')->nullable();
            $table->string('airtel_logo')->nullable();
            $table->string('glo_logo')->nullable();
            $table->string('9mobile_logo')->nullable();
            $table->string('app_logo')->nullable();
            $table->string('app_banner')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['mtn_logo', 'airtel_logo', 'glo_logo', '9mobile_logo', 'app_logo', 'app_banner']);
        });
    }
};
