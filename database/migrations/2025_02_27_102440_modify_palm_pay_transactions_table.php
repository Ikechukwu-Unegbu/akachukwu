<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('palm_pay_transactions');

        Schema::create('palm_pay_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('reference_id', 512)->unique();
            $table->string('trx_ref', 512)->nullable();
            $table->string('amount');
            $table->string('currency');
            $table->decimal('balance_before', 20, 2);
            $table->decimal('balance_after', 20, 2);
            $table->json('meta');
            $table->tinyInteger('status')->default(false);
            $table->enum('api_status', ['successful', 'processing', 'pending', 'failed'])->nullable();
            $table->timestamps();
        });
    }

     public function down(): void
    {
        Schema::dropIfExists('palm_pay_transactions');
    }
};

