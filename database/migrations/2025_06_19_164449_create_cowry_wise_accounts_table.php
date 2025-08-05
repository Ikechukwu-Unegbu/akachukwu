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
        Schema::create('cowry_wise_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('account_id')->unique();
            $table->string('account_type');
            $table->string('account_status');
            $table->boolean('is_verified')->default(false);
            $table->integer('risk_appetite')->default(0);
            $table->bigInteger('account_number')->nullable();
            $table->boolean('is_proprietary')->default(false);
            $table->string('verification_status')->default('UNVERIFIED');
            $table->dateTime('date_joined');
            $table->string('street')->nullable();
            $table->string('lga')->nullable();
            $table->string('area_code')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cowry_wise_accounts');
    }
};
