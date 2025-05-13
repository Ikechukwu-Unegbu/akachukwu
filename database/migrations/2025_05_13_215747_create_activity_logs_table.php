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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('resource_owner_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('activity');
            $table->string('resource')->nullable();

            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->json('raw_request')->nullable();
            $table->json('raw_response')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('location')->nullable();
            $table->decimal('balance_before', 20, 2)->nullable();
            $table->decimal('balance_after', 20, 2)->nullable();
            $table->json('tags')->nullable();
            $table->string('ref_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
