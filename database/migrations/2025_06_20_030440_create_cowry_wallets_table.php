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
        Schema::create('cowry_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cowry_wise_account_id')->constrained()->onDelete('cascade');
            $table->string('wallet_id')->unique();
            $table->string('bank_name')->nullable();
            $table->string('product_code')->nullable();
            $table->string('currency')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cowry_wallets');
    }
};
