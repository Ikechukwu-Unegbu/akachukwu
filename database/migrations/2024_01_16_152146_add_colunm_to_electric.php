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
        Schema::table('electric', function (Blueprint $table) {
            $table->integer('vendor_id')->after('id');
            $table->bigInteger('disco_id')->after('vendor_id');
            $table->string('disco_name')->after('disco_id');
            $table->tinyInteger('status')->after('disco_name')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('electric', function (Blueprint $table) {
            $table->dropColumn('vendor_id');
            $table->dropColumn('disco_id');
            $table->dropColumn('disco_name');
            $table->dropColumn('status');
        });
    }
};
