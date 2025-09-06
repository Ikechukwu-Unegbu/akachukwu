<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crypto_wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('currency', 20); // e.g., BTC, USDT-TRC20
            $table->string('address')->nullable();
            $table->string('network')->nullable();
            $table->string('provider')->default('quidax');
            $table->decimal('balance', 24, 8)->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'currency', 'network', 'provider']);
            $table->index(['user_id', 'currency']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crypto_wallets');
    }
};
