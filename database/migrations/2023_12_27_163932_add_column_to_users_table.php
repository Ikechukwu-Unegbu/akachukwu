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
            $table->decimal('account_balance', 8, 2)->after('password');
            $table->decimal('wallet_balance', 8, 2)->after('account_balance');
            $table->decimal('bonus_balance', 8, 2)->after('wallet_balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('account_balance');
            $table->dropColumn('wallet_balance');
            $table->dropColumn('bonus_balance');
        });
    }
};
