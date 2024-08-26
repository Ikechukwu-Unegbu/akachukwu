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
            $table->boolean('deactivation')->default(false);
            $table->boolean('blocked_by_admin')->default(false);
            $table->boolean('admin_block_reason')->nullable();
            $table->softDeletes();
            $table->unsignedBigInteger('soft_deleted_by')->nullable();
            $table->unsignedBigInteger('blocked_by')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('deactivation');
            $table->dropColumn('blocked_by_admin');
            $table->dropColumn('admin_block_reason');
            $table->dropSoftDeletes();
            $table->dropForeign(['soft_deleted_by']);
            $table->dropColumn('soft_deleted_by');
            $table->dropForeign(['blocked_by']);
            $table->dropColumn('blocked_by');
        });
    }
};
