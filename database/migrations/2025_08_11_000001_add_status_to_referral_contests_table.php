<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referral_contests', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->default(0)->after('active'); // 0=pending, 1=active, 2=ended
        });
    }

    public function down(): void
    {
        Schema::table('referral_contests', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
