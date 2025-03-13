<?php

namespace App\Livewire\Admin;

use App\Models\Data\DataTransaction;
use App\Models\Education\ResultCheckerTransaction;
use App\Models\MoneyTransfer;
use App\Models\User;
use App\Models\Utility\AirtimeTransaction;
use App\Models\Utility\CableTransaction;
use App\Models\Utility\ElectricityTransaction;
use App\Models\Vendor;
use App\Services\Vendor\GladTidingService;
use App\Services\Vendor\PosTraNetService;
use App\Services\Vendor\VTPassService;
use Livewire\Component;
use Livewire\Attributes\Lazy;

#[Lazy]
class Dashboard extends Component
{
    public $registeredUser = [];
    public $months = [];
    public $vtBalance;
    public $gladBalance;
    public $postranetBlance;
    public $total_wallet;

    public $filterByDate;

    public function mount()
    {
        $months = [];
        for ($m=1; $m<=12; $m++) $months[] = date('m', mktime(0,0,0,$m, 1, date('Y')));
        foreach ($months as $month) {
            $this->months[] = date('M', mktime(0,0,0,$month, 1, date('Y'))); 
            $this->registeredUser[] = User::whereRole('user')->whereRaw('MONTH(created_at) = ?', $month)->count();
        
        }

        $this->allVendorBalance();
        
        // $this->filterByDate = now()->toDateString();
    }

    public function updated()
    {
        // Send updated chart data to JavaScript after Livewire update
        $this->dispatch('updateChart', [
            'months' => $this->months,
            'registeredUser' => $this->registeredUser
        ]);
    }

    public function updatedFilterByDate()
    {
        $this->filterByDate = $this->filterByDate ?: now()->toDateString();
    }

    public function allVendorBalance()
    {
       

     
            $vtPass = Vendor::where('name', 'VTPASS')->first();
            $glad = Vendor::where('name', 'GLADTIDINGSDATA')->first();
            $postranet = Vendor::where('name', 'POSTRANET')->first();
            $vtService = new VTPassService($vtPass);
            $postranetService = new PosTraNetService($postranet);
            $gladService = new GladTidingService($glad);
            
            $vtBalance =  $vtService::getWalletBalance();
            $postranetBlance =  $postranetService::getWalletBalance();
            $gladBalance =  $gladService::getWalletBalance();
      


        $this->vtBalance = 0.00;
    
        $this->postranetBlance = ($postranetBlance?->status) ? $postranetBlance->response : 'N/A';
        $this->gladBalance = ($gladBalance?->status) ? $gladBalance->response : 'N/A';
        $this->total_wallet = \App\Models\User::sum('account_balance');

    }

    public function placeholder()
    {
        return view('livewire.component.placeholder');
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'customers_count'       =>    User::when($this->filterByDate, function ($query, $filterByDate) {
                return $query->whereDate('created_at', $filterByDate);
            })->whereRole('user')->count(),
            'airtime_sale'          =>    AirtimeTransaction::whereStatus(true)->whereDate('created_at', $this->filterByDate ?? now()->toDateString())->sum('amount'),
            'data_sale'             =>    DataTransaction::whereStatus(true)->whereDate('created_at', $this->filterByDate ?? now()->toDateString())->sum('amount'),
            'cable_sale'            =>    CableTransaction::whereStatus(true)->whereDate('created_at', $this->filterByDate ?? now()->toDateString())->sum('amount'),
            'electricity_sale'      =>    ElectricityTransaction::whereStatus(true)->whereDate('created_at', $this->filterByDate ?? now()->toDateString())->sum('amount'),
            'resellers_count'       =>    User::whereUserLevel('reseller')->count(),
            'result_checker_count'  =>    ResultCheckerTransaction::whereStatus(true)->whereDate('created_at', $this->filterByDate ?? now()->toDateString())->sum('amount'),
            'vendors'               =>    Vendor::with('balances')->get(),
            'vastel_transfer_count' =>    MoneyTransfer::where('type', 'internal')->whereDate('created_at', $this->filterByDate ?? now()->toDateString())->sum('amount'),
            'bank_transfer_count'   =>    MoneyTransfer::where('type', 'external')->whereDate('created_at', $this->filterByDate ?? now()->toDateString())->sum('amount'),
        ]);
    }
}
