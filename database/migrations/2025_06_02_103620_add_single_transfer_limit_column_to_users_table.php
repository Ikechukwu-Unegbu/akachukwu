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
            $table->decimal('single_transfer_limit', 10, 2)->nullable()->after('account_balance')->comment('Single transfer limit for the user');
            $table->decimal('daily_transfer_limit', 10, 2)->nullable()->after('single_transfer_limit')->comment('Daily transfer limit for the user');
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->decimal('single_transfer_limit', 10, 2)->nullable()->after('maximum_transfer')->comment('Single transfer limit for all users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['single_transfer_limit', 'daily_transfer_limit']);
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('single_transfer_limit');
        });
    }
};
