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
        Schema::create('crypto_transactions_logs', function (Blueprint $table) {
            $table->id();
            $table->string('txid', 512)->unique(); //transaction hash
            $table->string('transaction_id', 512)->unique();//deposit id
            $table->string('session_id', 512)->nullable();
            $table->string('order_no', 512)->nullable();
            $table->foreignId('user_id')->constrained();
            $table->decimal('amount', 20, 2);
            $table->decimal('amount_in_crypto', 20, 8)->default(0.00000000);
            $table->decimal('fee', 20, 2)->default(0.00);
            $table->string('currency')->default('ngn');
            $table->string('crypto_currency', 10)->default('btc');
            $table->string('wallet_address', 512)->nullable();
            $table->string('remark')->nullable();
            $table->decimal('balance_before', 20, 2)->default(0.00);
            $table->decimal('balance_after', 20, 2)->default(0.00);
            $table->string('status')->nullabe();
            // $table->enum('transfer_status', ['unpaid', 'paying', 'success', 'fail', 'close'])->nullable();
            // $table->enum('api_status', ['successful', 'processing', 'pending', 'failed'])->nullable();
            $table->json('meta')->nullable();;
            $table->json('api_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_transactions_logs');
    }
};
