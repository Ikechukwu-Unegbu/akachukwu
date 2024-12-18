<?php

namespace App\Livewire\Admin\Transaction;

use App\Models\Vendor;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use App\Models\Utility\Cable;
use Illuminate\Support\Facades\DB;
use App\Services\CalculateDiscount;
use Illuminate\Support\Facades\Log;
use App\Models\Data\DataTransaction;
use App\Models\Utility\CableTransaction;
use App\Models\Utility\AirtimeTransaction;
use App\Services\Vendor\VendorServiceFactory;
use App\Models\Utility\ElectricityTransaction;
use App\Services\Vendor\QueryVendorTransaction;
use App\Models\Education\ResultCheckerTransaction;

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
    public $transaction_type;
    public $transaction_id;
    public $get_transaction;

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
        $this->error_msg = "";
        $this->vendorResponse = "";
        $this->transaction_id = $id;
        $this->transaction_type = $type;
        $this->get_transaction = self::getTransactionTableByType($type, $id);
        
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
    
    public function handleRefund()
    {
        try {
            $transaction = self::getTransactionTableByType($this->transaction_type, $this->transaction_id);
            $amount = CalculateDiscount::calculate($transaction->amount, $transaction->discount);
            $transaction->user->setAccountBalance($amount);
            $transaction->refund();
            $this->dispatch('success-toastr', ['message' => 'Transaction Refunded Successfully']);
            session()->flash('success', 'Transaction Refunded Successfully');
            $this->redirect(url()->previous());
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $this->dispatch('success-toastr', ['message' => 'Unable to Refund User, Please try again later.']);
            session()->flash('success', 'Unable to Refund User, Please try again later.');
            $this->redirect(url()->previous());
        }
    }

    public function handleDebit()
    {
       try {
            $transaction = self::getTransactionTableByType($this->transaction_type, $this->transaction_id);
            $amount = CalculateDiscount::calculate($transaction->amount, $transaction->discount);
            $transaction->user->setTransaction($amount);
            $transaction->debit();
            $this->dispatch('success-toastr', ['message' => 'Transaction Debited Successfully']);
            session()->flash('success', 'Transaction Debited Successfully');
            $this->redirect(url()->previous());
       } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $this->dispatch('success-toastr', ['message' => 'Unable to Debited User, Please try again later.']);
            session()->flash('success', 'Unable to Debited User, Please try again later.');
            $this->redirect(url()->previous());
       }
    }

    public static function getTransactionTableByType($type, $id)
    {
        $transactionTables = [
            'airtime'        =>  AirtimeTransaction::class,
            'cable'          =>  CableTransaction::class,
            'data'           =>  DataTransaction::class,
            'electricity'    =>  ElectricityTransaction::class,
            'result_checker' =>  ResultCheckerTransaction::class
        ];

        // Check if the provided type exists in the mapping
        if (!isset($transactionTables[$type])) {
            // If type is invalid, return an error message
            throw new \InvalidArgumentException("Invalid transaction type: {$type}");
        }

        // Get the corresponding table name for the provided type
        $tableName = $transactionTables[$type];

        return  (new $tableName)->findOrFail($id);
    }
    
    public function render()
    {
        $query = DB::table(DB::raw('
            (
                SELECT id, transaction_id, user_id, amount, status, vendor_status, "data" as type, created_at FROM data_transactions
                UNION ALL
                SELECT id, transaction_id, user_id, amount, status, vendor_status, "airtime" as type, created_at FROM airtime_transactions
                UNION ALL
                SELECT id, transaction_id, user_id, amount, status, vendor_status, "cable" as type, created_at FROM cable_transactions
                UNION ALL
                SELECT id, transaction_id, user_id, amount, status, vendor_status, "electricity" as type, created_at FROM electricity_transactions
                UNION ALL
                SELECT id, transaction_id, user_id, amount, status, vendor_status, "education" as type, created_at FROM result_checker_transactions
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
