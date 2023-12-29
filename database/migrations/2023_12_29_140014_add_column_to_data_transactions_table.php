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
        Schema::table('data_transactions', function (Blueprint $table) {
            $table->string('api_data_id')->nullable()->after('plan_amount');
            $table->text('api_response')->nullable()->after('api_response');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_transactions', function (Blueprint $table) {
            $table->dropColumn('api_data_id');
            $table->dropColumn('api_response');
        });
    }
};
