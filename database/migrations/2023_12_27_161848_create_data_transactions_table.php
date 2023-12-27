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
        Schema::create('data_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('vendor_id');
            $table->integer('network_id');
            $table->integer('type_id');
            $table->integer('data_id');
            $table->decimal('amount', 8, 2);
            $table->string('size');
            $table->string('validity');
            $table->string('mobile_number');
            $table->string('balance_before')->nullable();
            $table->string('balance_after')->nullable();
            $table->string('plan_network');
            $table->string('plan_name');
            $table->decimal('plan_amount', 8, 2);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_transactions');
    }
};
