<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quidax_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('reference_id')->unique(); // Quidax transaction ID
            $table->string('trx_ref')->nullable(); // Blockchain transaction ID
            $table->decimal('amount', 18, 8); // Crypto amount
            $table->decimal('naira_amount', 18, 2); // Converted NGN amount
            $table->string('currency', 10); // Crypto currency (BTC, ETH, etc.)
            $table->string('status')->default('pending');
            $table->string('api_status')->default('pending');
            $table->text('meta')->nullable(); // Store full webhook payload
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('reference_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quidax_transactions');
    }
};
