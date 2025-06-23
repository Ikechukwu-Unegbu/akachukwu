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
        Schema::create('cowry_wise_savings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cowry_wise_account_id')->constrained()->onDelete('cascade');
            $table->string('savings_id')->unique();
            $table->string('name')->nullable();
            $table->string('product_code');
            $table->decimal('principal', 8, 2)->default(0.00);
            $table->decimal('returns', 8, 2)->default(0.00);
            $table->decimal('balance', 8, 2)->default(0.00);
            $table->boolean('interest_enabled')->default(false);
            $table->decimal('interest_rate', 8, 2)->default(0.00);
            $table->date('maturity_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cowry_wise_savings');
    }
};
