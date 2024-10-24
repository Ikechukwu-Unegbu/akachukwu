<?php

namespace App\Livewire\Admin\Transaction;

use App\Models\Vendor;
use App\Services\Vendor\QueryVendorTransaction;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Services\Vendor\VendorServiceFactory;

class QueryTransaction extends Component
{
    use WithPagination;

    public $perPage = 50;
    public $perPages = [50, 100, 200];
    public $search;

    public $type;
    public $startDate;
    public $endDate;
    public $status;
    public $selectedUser = [];
    public $vendorResponse;
    public $loader = true;
    public $error_msg;

    public function  mount(Request $request)
    {
        $this->authorize('view all transactions');
        $this->type = $request->input('type');
        $this->startDate = $request->input('start_date');
        $this->endDate = $request->input('end_date');
        $this->status = $request->input('status') ?? '';
    }

    public function queryTransaction($id, $type)
    {
        $query =  QueryVendorTransaction::initializeQuery($id, $type);
        
        if (!$query->status) {
            $this->dispatch('error-toastr', ['message' => $query->msg]);
            $this->error_msg = $query->msg;
            $this->loader = false;
            return;
        }

        $this->loader = false;
        $this->dispatch('success-toastr', ['message' => $query->msg]);
        $this->vendorResponse = $query->result;
        return;
    }

    public function handleModal()
    {
        $this->error_msg = "";
        $this->vendorResponse = "";
        $this->loader = true;
    }
    
    public function render()
    {
        $query = DB::table(DB::raw('
            (
                SELECT id, transaction_id, user_id, amount, status, "data" as type, created_at FROM data_transactions
                UNION ALL
                SELECT id, transaction_id, user_id, amount, status, "airtime" as type, created_at FROM airtime_transactions
                UNION ALL
                SELECT id, transaction_id, user_id, amount, status, "cable" as type, created_at FROM cable_transactions
                UNION ALL
                SELECT id, transaction_id, user_id, amount, status, "electricity" as type, created_at FROM electricity_transactions
                UNION ALL
                SELECT id, transaction_id, user_id, amount, status, "education" as type, created_at FROM result_checker_transactions
            ) as transactions
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

        if ($this->status !== "") {
            $query->where('transactions.status', (int) $this->status);
        }
        
        $transactions = $query->paginate($this->perPage);

        return view('livewire.admin.transaction.query-transaction', compact('transactions'));
    }
}
