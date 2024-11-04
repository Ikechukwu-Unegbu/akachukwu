<?php

namespace App\Livewire\Admin\Api\Vendor;

use App\Models\Vendor;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Http\Request;

class Account extends Component
{
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
        $query = Vendor::with('balances');

        if ($this->startDate) {
            $query->whereHas('balances', function ($q) {
                $q->where('date', '>=', $this->startDate);
            });
        }
        
        if ($this->endDate) {
            $query->whereHas('balances', function ($q) {
                $q->where('date', '<=', $this->endDate);
            });
        }

        if (!$this->startDate && !$this->endDate) {
            $query->whereHas('balances', function ($q) {
                $q->whereDate('date', Carbon::today());
            });
        }
        
        $vendors = $query->get();

        return view('livewire.admin.api.vendor.account', [
            'vendors' => $vendors
        ]);
    }
}
