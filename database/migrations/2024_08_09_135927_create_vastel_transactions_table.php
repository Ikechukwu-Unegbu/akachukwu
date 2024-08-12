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
        Schema::create('vastel_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_id', 512);
            $table->integer('amount');
            $table->tinyInteger('type')->default(false);
            $table->string('currency')->nullable();
            $table->json('meta')->nullable();
            $table->tinyInteger('status')->default(false);
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vastel_transactions');
    }
};
