<?php

use App\Models\Bank;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $bankExists = Bank::where('name', 'MONIEPOINT')->first();

        if ($bankExists) $bankExists->update(['va_status' => false]);

        $exists = Bank::where('code', '50515')->exists();

        if (!$exists) {
            Bank::create([
                'name' => 'MONIEPOINT',
                'code' => '50515',
                'nip_bank_code' => '090405',
                'base_ussd_code' => '*5573#',
                'va_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
