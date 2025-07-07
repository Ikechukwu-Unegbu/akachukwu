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
        // $this->down();
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('flagged_by_admin_id')->nullable();
            $table->timestamp('flagged_at')->nullable();
            $table->unsignedBigInteger('post_no_debit_by_admin_id')->nullable();
            $table->timestamp('post_no_debit_at')->nullable();
            $table->unsignedBigInteger('blacklisted_by_admin_id')->nullable();
            $table->timestamp('blacklisted_at')->nullable();

            $table->foreign('flagged_by_admin_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('post_no_debit_by_admin_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('blacklisted_by_admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['flagged_by_admin_id']);
            $table->dropForeign(['post_no_debit_by_admin_id']);
            $table->dropForeign(['blacklisted_by_admin_id']);
            $table->dropColumn([
                'flagged_by_admin_id',
                'flagged_at',
                'post_no_debit_by_admin_id',
                'post_no_debit_at',
                'blacklisted_by_admin_id',
                'blacklisted_at',
            ]);
        });
    }
};
