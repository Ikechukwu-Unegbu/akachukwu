<?php

namespace App\Livewire\Admin;

use App\Models\Data\DataTransaction;
use App\Models\Education\ResultCheckerTransaction;
use App\Models\User;
use App\Models\Utility\AirtimeTransaction;
use App\Models\Utility\CableTransaction;
use App\Models\Utility\ElectricityTransaction;
use App\Models\Vendor;
use App\Services\Vendor\GladTidingService;
use App\Services\Vendor\PosTraNetService;
use App\Services\Vendor\VTPassService;
use Livewire\Component;

class Dashboard extends Component
{
    public $registeredUser = [];
    public $months = [];
    public $vtBalance;
    public $gladBalance;
    public $postranetBlance;
    public $total_wallet;

    public function mount()
    {
        $months = [];
        for ($m=1; $m<=12; $m++) $months[] = date('m', mktime(0,0,0,$m, 1, date('Y')));
        foreach ($months as $month) {
            $this->months[] = date('M', mktime(0,0,0,$month, 1, date('Y'))); 
            $this->registeredUser[] = User::whereRole('user')->whereRaw('MONTH(created_at) = ?', $month)->count();
        
        }

        $this->allVendorBalance();
        
    }

    public function allVendorBalance()
    {
       

        try {
            
            $vtPass = Vendor::where('name', 'VTPASS')->first();
            $glad = Vendor::where('name', 'GLADTIDINGSDATA')->first();
            $postranet = Vendor::where('name', 'POSTRANET')->first();
            $vtService = new VTPassService($vtPass);
            $postranetService = new PosTraNetService($postranet);
            $gladService = new GladTidingService($glad);
            
            $vtBalance =  $vtService::getWalletBalance();
            $postranetBlance =  $postranetService::getWalletBalance();
            $gladBalance =  $gladService::getWalletBalance();
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve wallet balance: ' . $e->getMessage());
            // Handle the error appropriately
        }


        $this->vtBalance = ($vtBalance && isset($vtBalance->status) && $vtBalance->status) 
        ? $vtBalance->response 
        : 'N/A';
    
        $this->postranetBlance = ($postranetBlance->status) ? $postranetBlance->response : 'N/A';
        $this->gladBalance = ($gladBalance->status) ? $gladBalance->response : 'N/A';
        $this->total_wallet = \App\Models\User::sum('account_balance');

    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'customers_count'       =>    User::whereRole('user')->count(),
            'airtime_sale'          =>    AirtimeTransaction::whereStatus(true)->whereDate('created_at', now()->toDateString())->sum('amount'),
            'data_sale'             =>    DataTransaction::whereStatus(true)->whereDate('created_at', now()->toDateString())->sum('amount'),
            'cable_sale'            =>    CableTransaction::whereStatus(true)->whereDate('created_at', now()->toDateString())->sum('amount'),
            'electricity_sale'      =>    ElectricityTransaction::whereStatus(true)->whereDate('created_at', now()->toDateString())->sum('amount'),
            'resellers_count'       =>    User::whereUserLevel('reseller')->count(),
            'result_checker_count'  =>    ResultCheckerTransaction::whereStatus(true)->whereDate('created_at', now()->toDateString())->sum('amount'),
        ]);
    }
}
