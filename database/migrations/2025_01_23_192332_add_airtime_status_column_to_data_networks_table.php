<?php

use App\Models\Data\DataNetwork;
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
        Schema::table('data_networks', function (Blueprint $table) {
            $table->tinyInteger('airtime_status')->default(false)->after('status');
        });

        DataNetwork::where('status', true)->update(['airtime_status' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_networks', function (Blueprint $table) {
            $table->dropColumn('airtime_status');
        });
    }
};
