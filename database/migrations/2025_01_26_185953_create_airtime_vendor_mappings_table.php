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
        Schema::create('airtime_vendor_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('data_vendors')->onDelete('cascade');
            $table->string('network');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airtime_vendor_mappings');
    }
};
