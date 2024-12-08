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
        Schema::table('money_transfers', function (Blueprint $table) {
            $table->unsignedBigInteger('recipient')->nullable();
            $table->string('type')->default('external');
            $table->string('bank_code')->nullable()->change();
            $table->string('account_number')->nullable()->change();
            $table->decimal('amount', 20, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('money_transfers', function (Blueprint $table) {
            $table->dropColumn(['type', 'recipient']);
            $table->string('bank_code')->nullable(false)->change();
            $table->string('account_number')->nullable(false)->change();
            $table->integer('amount')->change();
        });
    }
};
