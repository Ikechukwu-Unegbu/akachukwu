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
        Schema::create('result_checker_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->string('reference_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('result_checker_id');
            $table->string('exam_name');
            $table->string('quantity');
            $table->decimal('amount', 20, 2);
            $table->string('balance_before')->nullable();
            $table->string('balance_after')->nullable();
            $table->string('api_data_id')->nullable();
            $table->text('api_response')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_checker_transactions');
    }
};
