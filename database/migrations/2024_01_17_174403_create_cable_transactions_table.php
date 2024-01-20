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
        Schema::create('cable_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('vendor_id');
            $table->string('cable_name');
            $table->integer('cable_id');
            $table->string('cable_plan_name');
            $table->integer('cable_plan_id');
            $table->string('smart_card_number');
            $table->string('customer_name');
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
        Schema::dropIfExists('cable_transactions');
    }
};
