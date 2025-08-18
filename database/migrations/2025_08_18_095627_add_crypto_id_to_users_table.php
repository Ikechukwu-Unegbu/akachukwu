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
        Schema::table('users', function (Blueprint $table) {
            $table->string('quidax_id')->nullable();
            $table->string('quidax_sn')->nullable();
            $table->string('quidax_display_name')->nullable();
            $table->string('quidax_reference')->nullable();
            $table->string('quidax_created_at')->nullable();
            $table->string('quidax_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
