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
        Schema::table('virtual_accounts', function (Blueprint $table) {
            $table->string('reservation_reference')->nullable()->change();
            $table->string('reserved_account_type')->nullable()->change();
            $table->string('restrict_payment_source')->nullable()->change();
            $table->string('collection_channel')->nullable()->change();
            $table->string('created_on')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('virtual_accounts', function (Blueprint $table) {
            $table->string('reservation_reference')->nullable(false)->change();
            $table->string('reserved_account_type')->nullable(false)->change();
            $table->string('restrict_payment_source')->nullable(false)->change();
            $table->string('collection_channel')->nullable(false)->change();
            $table->string('created_on')->nullable(false)->change();
        });
    }
};
