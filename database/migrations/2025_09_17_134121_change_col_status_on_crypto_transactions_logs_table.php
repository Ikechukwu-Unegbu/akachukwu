<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crypto_transactions_logs', function (Blueprint $table) {
            // Just change nullability, don't touch the unique index
            $table->string('txid', 512)->nullable()->change();
            $table->string('transaction_id', 512)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('crypto_transactions_logs', function (Blueprint $table) {
            $table->string('txid', 512)->nullable(false)->change();
            $table->string('transaction_id', 512)->nullable(false)->change();
        });
    }
};
