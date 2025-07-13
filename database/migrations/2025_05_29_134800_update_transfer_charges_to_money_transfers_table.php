<?php

use App\Models\SiteSetting;
use App\Models\MoneyTransfer;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = SiteSetting::first();

        if ($settings && $settings->transfer_charges) {
            if (Schema::hasColumn('money_transfers', 'charges')) {
                MoneyTransfer::isExternal()->update(['charges' => $settings->transfer_charges]);
            }
        }
    }
};
