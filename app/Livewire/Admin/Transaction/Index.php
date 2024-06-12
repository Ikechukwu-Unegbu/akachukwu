<?php

namespace App\Livewire\Admin\Transaction;

use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public $perPage = 50;
    public $perPages = [50, 100, 200];
    public $search;

    public $type;
    public $startDate;
    public $endDate;

    public function  mount(Request $request)
    {
        $this->type = $request->input('type');
        $this->startDate = $request->input('start_date');
        $this->endDate = $request->input('end_date');
    }


    public function render(Request $request)
    {
        $query = DB::table(DB::raw('
            (SELECT id, transaction_id, user_id, amount, status, "data" as type, created_at FROM data_transactions
            UNION ALL
            SELECT id, transaction_id, user_id, amount, status, "airtime" as type, created_at FROM airtime_transactions
            UNION ALL
            SELECT id, transaction_id, user_id, amount, status, "cable" as type, created_at FROM cable_transactions
            UNION ALL
            SELECT id, transaction_id, user_id, amount, status, "electricity" as type, created_at FROM electricity_transactions) as transactions
        '))->join('users', 'transactions.user_id', '=', 'users.id')
            ->select('transactions.*', 'users.name as user_name')
            ->when($this->search, function ($query, $search) {
                $query->where('transaction_id', 'LIKE', "%$search%")
                    ->orWhere('users.name', 'LIKE', "%$search%")
                    ->orWhere('type', 'LIKE', "%$search%");
            })
            ->orderBy('transactions.created_at', 'desc');

        if ($this->type) {
            $query->where('transactions.type', $this->type);
        }
        
        if ($this->startDate) {
            $query->where('transactions.created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->where('transactions.created_at', '<=', $this->endDate);
        }

        $transactions = $query->paginate($this->perPage);

        return view('livewire.admin.transaction.index', compact('transactions'));
    }
}
