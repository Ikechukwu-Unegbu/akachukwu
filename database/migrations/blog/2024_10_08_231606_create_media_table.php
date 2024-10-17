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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_name')->nullable();
            $table->string('path')->nullable();
            $table->enum('type', ['image', 'video', 'audio', 'document']);
            $table->string('mime_type')->nullable();
         
            $table->unsignedBigInteger('size')->nullable();
            $table->string('extension')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_featured')->default(false)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
