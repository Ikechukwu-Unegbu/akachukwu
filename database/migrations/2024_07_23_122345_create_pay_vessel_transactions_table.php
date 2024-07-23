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
        Schema::create('pay_vessel_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('reference_id', 512);
            $table->string('trx_ref', 512)->nullable();
            $table->string('amount');
            $table->string('currency');
            $table->json('meta');
            $table->tinyInteger('status')->default(false);
            $table->timestamps();
            $table->unique('reference_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_vessel_transactions');
    }
};
