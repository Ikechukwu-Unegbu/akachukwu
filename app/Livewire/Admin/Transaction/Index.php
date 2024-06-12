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


    public function render(Request $request)
    {
        $type = $request->input('type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
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

        if ($type) {
            $query->where('transactions.type', $type);
        }
        
        if ($startDate) {
            $query->where('transactions.created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('transactions.created_at', '<=', $endDate);
        }

        $transactions = $query->paginate($this->perPage);

        return view('livewire.admin.transaction.index', compact('transactions'));
    }
}
