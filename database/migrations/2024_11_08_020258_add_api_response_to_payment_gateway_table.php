<?php

use Illuminate\Support\Facades\DB;
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
        Schema::table('flutterwave_transactions', function (Blueprint $table) {
            $table->enum('api_status', ['successful', 'processing', 'pending', 'failed'])->after('status')->nullable();
        });

        Schema::table('paystack_transactions', function (Blueprint $table) {
            $table->enum('api_status', ['successful', 'processing', 'pending', 'failed'])->after('status')->nullable();
        });

        Schema::table('pay_vessel_transactions', function (Blueprint $table) {
            $table->enum('api_status', ['successful', 'processing', 'pending', 'failed'])->after('status')->nullable();
        });

        Schema::table('monnify_transactions', function (Blueprint $table) {
            $table->enum('api_status', ['successful', 'processing', 'pending', 'failed'])->after('status')->nullable();
        });

        Schema::table('vastel_transactions', function (Blueprint $table) {
            $table->enum('api_status', ['successful', 'processing', 'pending', 'failed'])->after('status')->nullable();
        });

        $this->run_response_query();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flutterwave_transactions', function (Blueprint $table) {
            $table->dropColumn('api_status');
        });

        Schema::table('paystack_transactions', function (Blueprint $table) {
            $table->dropColumn('api_status');
        });

        Schema::table('pay_vessel_transactions', function (Blueprint $table) {
            $table->dropColumn('api_status');
        });

        Schema::table('monnify_transactions', function (Blueprint $table) {
            $table->dropColumn('api_status');
        });

        Schema::table('vastel_transactions', function (Blueprint $table) {
            $table->dropColumn('api_status');
        });
    }

    public function run_response_query()
    {
        collect([
            'flutterwave_transactions', 
            'paystack_transactions', 
            'pay_vessel_transactions', 
            'monnify_transactions', 
            'vastel_transactions'
        ])->each(function ($table) {
            DB::table($table) 
            ->where('status', 0)
            ->update(['api_status' => 'failed']);

            DB::table($table)
            ->where('status', 1)
            ->update(['api_status' => 'successful']);
        }); 
    }
};
