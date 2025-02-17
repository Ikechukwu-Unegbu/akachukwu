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
        Schema::create('palm_pay_transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('reference_id', 512);
            $table->string('transaction_id', 512);
            $table->string('session_id', 512)->nullable();
            $table->string('order_no', 512)->nullable();
            $table->foreignId('user_id')->constrained();
            $table->decimal('amount', 20, 2);
            $table->decimal('fee', 20, 2)->default(0.00);
            $table->string('account_name');
            $table->string('account_number');
            $table->unsignedBigInteger('bank_id');
            $table->string('bank_code');
            $table->string('currency')->nullable();
            $table->string('remark')->nullable();
            $table->decimal('balance_before', 20, 2)->default(0.00);
            $table->decimal('balance_after', 20, 2)->default(0.00);
            $table->integer('status')->default(0);
            $table->enum('transfer_status', ['unpaid', 'paying', 'success', 'fail', 'close'])->nullable();
            $table->enum('api_status', ['successful', 'processing', 'pending', 'failed'])->nullable();
            $table->json('meta')->nullable();;
            $table->json('api_response')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('palm_pay_transactions');
    }
};
