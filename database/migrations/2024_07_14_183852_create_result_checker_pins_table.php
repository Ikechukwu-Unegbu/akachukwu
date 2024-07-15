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
        Schema::create('result_checker_pins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('result_checker_id');
            $table->string('serial');
            $table->string('pin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_checker_pins');
    }
};
