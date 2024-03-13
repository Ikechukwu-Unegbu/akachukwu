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
        Schema::create('monnify_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('reference_id', 512);
            $table->string('trx_ref', 512)->nullable();
            $table->integer('amount');
            $table->string('currency');
            $table->string('redirect_url');
            $table->json('meta');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->unique('reference_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monnify_transactions');
    }
};
