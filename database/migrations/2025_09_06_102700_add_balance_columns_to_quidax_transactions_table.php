<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quidax_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('quidax_transactions', 'balance_before')) {
                $table->decimal('balance_before', 18, 2)->nullable()->after('currency');
            }
            if (!Schema::hasColumn('quidax_transactions', 'balance_after')) {
                $table->decimal('balance_after', 18, 2)->nullable()->after('balance_before');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quidax_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('quidax_transactions', 'balance_before')) {
                $table->dropColumn('balance_before');
            }
            if (Schema::hasColumn('quidax_transactions', 'balance_after')) {
                $table->dropColumn('balance_after');
            }
        });
    }
};
