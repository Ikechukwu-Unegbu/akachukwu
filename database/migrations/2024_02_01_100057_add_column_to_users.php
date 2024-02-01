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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->after('name')->unique();
            $table->string('image')->after('password')->nullable();
            $table->string('address')->after('image')->nullable();
            $table->string('mobile')->after('address')->nullable();
            $table->string('gender')->after('mobile')->nullable();
            $table->string('referer_username')->after('mobile')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('image');
            $table->dropColumn('address');
            $table->dropColumn('mobile');
            $table->dropColumn('gender');
            $table->dropColumn('referer_username');
        });
    }
};
