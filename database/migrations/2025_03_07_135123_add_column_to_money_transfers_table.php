<?php

use App\Models\MoneyTransfer;
use App\Models\SiteSetting;
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
            $table->decimal('balance_after_refund')->default(0.00);
            $table->decimal('charges')->default(0.00);
        });

        $this->updateCharges();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('money_transfers', function (Blueprint $table) {
            $table->dropColumn(['balance_after_refund', 'charges']);
        });
    }

    public function updateCharges() : void
    {
        $settings = SiteSetting::first();
        if ($settings->transfer_charges) {
            MoneyTransfer::query()->update(['charges' => $settings->transfer_charges]);
        }
    }
};
