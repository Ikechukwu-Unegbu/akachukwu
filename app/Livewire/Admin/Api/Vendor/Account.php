<?php

namespace App\Livewire\Admin\Api\Vendor;

use Carbon\Carbon;
use App\Models\Vendor;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use App\Models\VendorBalance;

class Account extends Component
{
    use WithPagination;
    
    public $perPage = 50;
    public $perPages = [50, 100, 200];
    public $startDate;
    public $endDate;

    public function  mount(Request $request)
    {
        $this->authorize('view vendor api');   
        $this->startDate = $request->input('start_date');
        $this->endDate = $request->input('end_date');
    }

    public function render()
    {
        $query = VendorBalance::with('vendor');

        if ($this->startDate) {
            $query->where('date', '>=', $this->startDate);
        }
        
        if ($this->endDate) {
            $query->where('date', '<=', $this->endDate);
        }

        // if (!$this->startDate && !$this->endDate) {
        //     $query->whereDate('date', Carbon::today());
        // }
        
        $vendor_balance = $query->latest()->paginate($this->perPage);

        return view('livewire.admin.api.vendor.account', [
            'vendor_balance' => $vendor_balance
        ]);
    }
}
