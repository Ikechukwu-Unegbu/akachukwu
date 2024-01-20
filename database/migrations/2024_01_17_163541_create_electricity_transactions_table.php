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
        Schema::create('electricity_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('vendor_id');
            $table->integer('disco_id');
            $table->string('disco_name');
            $table->string('meter_number');
            $table->integer('meter_type_id');
            $table->string('meter_type_name');
            $table->decimal('amount', 8, 2);
            $table->string('customer_mobile_number')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_address')->nullable();
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
        Schema::dropIfExists('electricity_transactions');
    }
};
