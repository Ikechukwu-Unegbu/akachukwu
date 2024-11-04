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
            foreach (Vendor::get() as $vendor) {
                $this->info("Handling balance for vendor: {$vendor->name}");

                $this->authenticateSuperAdmin();
                $balance = $this->getVendorBalance($vendor);

                if ($balance !== null) {
                    $this->recordStartingBalance($vendor, $balance);
                    $this->recordClosingBalance($vendor, $balance);
                    $this->info("Vendor balance recorded successfully for: {$vendor->name}");
                    $this->info("------------------------------------------------------"  . PHP_EOL);
                } else {
                    $this->info("Failed to retrieve balance for vendor: {$vendor->name}" . PHP_EOL);
                }
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $this->info("An error occurred: {$th->getMessage()}");
        } finally {
            $this->logoutSuperAdmin();
        }
    }

    protected function authenticateSuperAdmin()
    {
        $userId = User::where('role', 'superadmin')->first()->id;
        auth()->loginUsingId($userId);
        $this->info("Super Admin authenticated successfully.");
    }

    protected function logoutSuperAdmin()
    {
        auth()->logout();
        $this->info("Super Admin logged out successfully.");
    }

    protected function getVendorBalance($vendor)
    {
        $vendorServiceFactory = $this->venderServiceFactory::make($vendor);
        $balanceResponse = $vendorServiceFactory::getWalletBalance();

        if (isset($balanceResponse->status) && $balanceResponse->status) {
            $this->info("Retrieved balance for vendor: {$vendor->name}");
            return str_replace(',', '', $balanceResponse->response);
        }

        $this->info("Balance status is not successful for vendor: {$vendor->name}");
        return null;
    }

    protected function recordStartingBalance($vendor, $vendorWalletBalance)
    {
        $today = Carbon::today();
        $existingBalance = VendorBalance::where('vendor_id', $vendor->id)
            ->where('date', $today)
            ->first();

        if (!$existingBalance) {
            VendorBalance::create([
                'vendor_id' => $vendor->id,
                'date' => $today,
                'starting_balance' => $vendorWalletBalance,
                'closing_balance' => 0.00,
            ]);
            $this->info("Starting balance recorded for vendor: {$vendor->name}");
        } else {
            $this->info("Starting balance already exists for vendor: {$vendor->name}");
        }
    }

    protected function recordClosingBalance($vendor, $vendorWalletBalance)
    {
        $today = Carbon::today();
        $vendor->refresh();
        
        $existingBalance = VendorBalance::where('vendor_id', $vendor->id)
            ->where('date', $today)
            ->first();

        if ($existingBalance) {
            $existingBalance->update([
                'closing_balance' => $vendorWalletBalance,
            ]);
            $this->info("Closing balance updated for vendor: {$vendor->name}");
        } else {
            VendorBalance::create([
                'vendor_id' => $vendor->id,
                'date' => $today,
                'closing_balance' => $vendorWalletBalance,
            ]);
            $this->info("Closing balance recorded for vendor: {$vendor->name}");
        }
    }
}
