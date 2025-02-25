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
        Schema::table('banks', function (Blueprint $table) {
            $table->string('type')->default('monnify')->after('id');
            $table->string('ussd_template')->nullable()->change(); 
            $table->string('image', 512)->nullable()->after('code');
            $table->enum('status', [true, false])->default(true)->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->string('ussd_template')->nullable(false)->change();
            $table->dropColumn('image');
            $table->dropColumn('status');
        });
    }
};
