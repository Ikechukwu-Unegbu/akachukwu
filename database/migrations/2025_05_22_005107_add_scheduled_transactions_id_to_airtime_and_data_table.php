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
        Schema::table('airtime_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('scheduled_transaction_id')->nullable()->after('vendor_status');
            $table->foreign('scheduled_transaction_id')->references('id')->on('scheduled_transactions')->onDelete('set null');
        });

        Schema::table('data_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('scheduled_transaction_id')->nullable()->after('vendor_status');
            $table->foreign('scheduled_transaction_id')->references('id')->on('scheduled_transactions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('airtime_transactions', function (Blueprint $table) {
            $table->dropColumn('scheduled_transactions_id');
        });

        Schema::table('data_transactions', function (Blueprint $table) {
            $table->dropColumn('scheduled_transactions_id');
        });
    }
};
