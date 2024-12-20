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
        Schema::table('money_transfers', function (Blueprint $table) {
            $table->decimal('sender_balance_before', 20, 2)->after('amount');
            $table->decimal('sender_balance_after', 20, 2)->after('sender_balance_before');
            $table->decimal('recipient_balance_before', 20, 2)->after('sender_balance_after');
            $table->decimal('recipient_balance_after', 20, 2)->after('recipient_balance_before');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('money_transfers', function (Blueprint $table) {
            $table->dropColumn('sender_balance_before');
            $table->dropColumn('sender_balance_after');
            $table->dropColumn('recipient_balance_before');
            $table->dropColumn('recipient_balance_after');
        });
    }
};
