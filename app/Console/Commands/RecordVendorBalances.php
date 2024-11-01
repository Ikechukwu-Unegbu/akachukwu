<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorBalance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\Vendor\VendorServiceFactory;

class RecordVendorBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vendor:record-balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Record the starting and closing balances for all vendors';

    public function __construct(
        protected VendorServiceFactory $venderServiceFactory
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle() 
    {
        try {
            foreach (Vendor::get() as $vendor) 
                $this->handleVendorBalance($vendor);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    protected function handleVendorBalance($vendor)
    {
        $today = Carbon::today();
        $userId = User::where('role', 'superadmin')->first()->id;
        auth()->loginUsingId($userId);

        $venderServiceFactory = $this->venderServiceFactory::make($vendor);
        $getBalance = $venderServiceFactory::getWalletBalance();

        if (isset($getBalance->status)) {
            if ($getBalance->status) {
                // Record starting balance
                $vendor_wallet_balance = $fixedValue = str_replace(',', '', $getBalance->response);
                $startingBalance = VendorBalance::where('vendor_id', $vendor->id)->where('date', $today)->first();

                if (!$startingBalance) {
                    VendorBalance::create([
                        'vendor_id' => $vendor->id,
                        'date' => $today,
                        'starting_balance' => $vendor_wallet_balance,
                        'closing_balance' => 0.00,
                    ]);
                }
                // Record closing balance
                $vendor->refresh();
                $closingBalance = VendorBalance::where('vendor_id', $vendor->id)->where('date', $today)->first();
                if ($closingBalance) {
                    $closingBalance->update([
                        'closing_balance' => $vendor_wallet_balance,
                    ]);
                } else {
                    VendorBalance::create([
                        'vendor_id' => $vendor->id,
                        'date' => $today,
                        'closing_balance' => $vendor_wallet_balance,
                    ]);
                }
            }
        }
        $this->info('Vendor balance recorded successfully.');
    }
}
