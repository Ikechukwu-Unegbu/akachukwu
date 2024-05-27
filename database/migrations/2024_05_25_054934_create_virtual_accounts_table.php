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
        Schema::create('virtual_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('reference');
            $table->string('bank_code');
            $table->string('bank_name');
            $table->string('account_name');
            $table->string('account_number');
            $table->string('bvn')->nullable();
            $table->string('nin')->nullable();
            $table->string('reservation_reference');
            $table->string('reserved_account_type');
            $table->string('restrict_payment_source');
            $table->string('collection_channel');
            $table->string('status');
            $table->timestamp('created_on');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_accounts');
    }
};
