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
        Schema::create('cowry_wise_saving_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 512)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cowry_wise_savings_id')->constrained()->onDelete('cascade');
            $table->foreignId('cowry_wallet_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 8, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->string('transfer_type')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cowry_wise_saving_transactions');
    }
};
