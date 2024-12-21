<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('money_transfers', function (Blueprint $table) {
            $table->enum('transfer_status', ['successful', 'processing', 'pending', 'failed', 'refunded'])->after('status')->nullable();
        });

        DB::table('money_transfers') 
        ->where('status', true)
        ->update(['transfer_status' => 'successful']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('money_transfers', function (Blueprint $table) {
            $table->dropColumn('transfer_status');
        });
    }
};
