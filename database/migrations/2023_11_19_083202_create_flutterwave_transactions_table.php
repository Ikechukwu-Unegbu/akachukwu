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
        Schema::create('flutterwave_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_id', 512);
            $table->integer('amount');
            $table->string('currency');
            $table->string('redirect_url');
            $table->json('meta');
            $table->timestamps();
            $table->unique('reference_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flutterwave_transactions');
    }
};
