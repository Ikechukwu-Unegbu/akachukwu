<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable();
            $table->string('house_number')->nullable();
            $table->text('street_address')->nullable();
            $table->string('local_government')->nullable();
            $table->string('area_code')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();

            // Next of Kin Information (Tier 2)
            $table->string('next_of_kin_relationship')->nullable();
            $table->string('next_of_kin_first_name')->nullable();
            $table->string('next_of_kin_last_name')->nullable();
            $table->string('next_of_kin_email')->nullable();
            $table->string('next_of_kin_phone')->nullable();
            $table->enum('next_of_kin_gender', ['male', 'female', 'other'])->nullable();

            // Document Uploads
            $table->enum('utility_bill_type', ['electricity', 'waste_water', 'rent_tenancy', 'internet', 'other'])->nullable();
            $table->string('utility_bill_path')->nullable();
            $table->enum('government_id_type', ['national_id', 'voters_card', 'drivers_license', 'international_passport'])->nullable();
            $table->string('government_id_path')->nullable();

            // Tier Completion Tracking
            $table->boolean('tier_1_completed')->default(false);
            $table->timestamp('tier_1_completed_at')->nullable();
            $table->boolean('tier_2_completed')->default(false);
            $table->timestamp('tier_2_completed_at')->nullable();
            $table->boolean('tier_3_completed')->default(false);
            $table->timestamp('tier_3_completed_at')->nullable();

            // Overall KYC Status
            $table->boolean('kyc_completed')->default(false);
            $table->timestamp('kyc_completed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'house_number',
                'street_address',
                'local_government',
                'area_code',
                'city',
                'state',
                'next_of_kin_relationship',
                'next_of_kin_first_name',
                'next_of_kin_last_name',
                'next_of_kin_email',
                'next_of_kin_phone',
                'next_of_kin_gender',
                'utility_bill_type',
                'utility_bill_path',
                'government_id_type',
                'government_id_path',
                'tier_1_completed',
                'tier_1_completed_at',
                'tier_2_completed',
                'tier_2_completed_at',
                'tier_3_completed',
                'tier_3_completed_at',
                'kyc_completed',
                'kyc_completed_at',
            ]);
        });
    }
};
