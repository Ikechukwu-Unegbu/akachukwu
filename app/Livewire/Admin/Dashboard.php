<?php

namespace App\Livewire\Admin;

use App\Models\Data\DataTransaction;
use App\Models\User;
use App\Models\Utility\AirtimeTransaction;
use App\Models\Utility\CableTransaction;
use App\Models\Utility\ElectricityTransaction;
use Livewire\Component;

class Dashboard extends Component
{
    public $registeredUser = [];
    public $months = [];

    public function mount()
    {
        $months = [];
        for ($m=1; $m<=12; $m++) $months[] = date('m', mktime(0,0,0,$m, 1, date('Y')));
        foreach ($months as $month) {
            $this->months[] = date('M', mktime(0,0,0,$month, 1, date('Y'))); 
            $this->registeredUser[] = User::whereRole('user')->whereRaw('MONTH(created_at) = ?', $month)->count();
        
        }
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'customers_count'       =>    User::whereRole('user')->count(),
            'airtime_sale'          =>    AirtimeTransaction::whereStatus(true)->whereDate('created_at', now()->toDateString())->sum('amount'),
            'data_sale'             =>    DataTransaction::whereStatus(true)->whereDate('created_at', now()->toDateString())->sum('amount'),
            'cable_sale'            =>    CableTransaction::whereStatus(true)->whereDate('created_at', now()->toDateString())->sum('amount'),
            'electricity_sale'      =>    ElectricityTransaction::whereStatus(true)->whereDate('created_at', now()->toDateString())->sum('amount')
        ]);
    }
}
