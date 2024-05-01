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
        Schema::create('money_transfers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('reference_id', 512);
            $table->string('trx_ref', 512)->nullable();
            $table->integer('amount');
            $table->text('narration')->nullable();
            $table->string('bank_code');
            $table->string('bank_name')->nullable();
            $table->string('account_number');
            $table->text('comment')->nullable();
            $table->string('currency')->default('NGN');
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
        Schema::dropIfExists('money_transfers');
    }
};
