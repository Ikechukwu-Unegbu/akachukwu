<?php

namespace App\Livewire\Admin\Transaction;

use App\Models\Data\DataTransaction;
use App\Models\Education\ResultCheckerTransaction;
use App\Models\Utility\AirtimeTransaction;
use App\Models\Utility\CableTransaction;
use App\Models\Utility\ElectricityTransaction;
use App\Services\CalculateDiscount;
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
    public $status;
    public $selectedUser = [];

    public function  mount(Request $request)
    {
        $this->authorize('view all transactions');
        $this->type = $request->input('type');
        $this->startDate = $request->input('start_date');
        $this->endDate = $request->input('end_date');
        $this->status = $request->input('status') ?? '';
    }

    public function performRefund()
    {
        if (!count($this->selectedUser)) {
            return $this->dispatch('error-toastr', ['message' => "Select User(s) to Perform Refund."]);
        }

        if (isset($this->selectedUser['data'])) {
            foreach (array_keys($this->selectedUser['data']) as $data) {
                $dataTransaction = DataTransaction::where('transaction_id', $data)->first();
                $amount = CalculateDiscount::calculate($dataTransaction->amount, $dataTransaction->discount);
                $dataTransaction->user->setAccountBalance($amount);
                $dataTransaction->update(['status' => 2]);
            }
        }

        if (isset($this->selectedUser['airtime'])) {
            foreach (array_keys($this->selectedUser['airtime']) as $airtime) {
                $airtimeTransaction = AirtimeTransaction::where('transaction_id', $airtime)->first();
                $amount = CalculateDiscount::calculate($airtimeTransaction->amount, $airtimeTransaction->discount);
                $airtimeTransaction->user->setAccountBalance($amount);
                $airtimeTransaction->update(['status' => 2]);
            }
        }

        if (isset($this->selectedUser['cable'])) {
            foreach (array_keys($this->selectedUser['cable']) as $cable) {
                $cableTransaction = CableTransaction::where('transaction_id', $cable)->first();
                $amount = CalculateDiscount::calculate($cableTransaction->amount, $cableTransaction->discount);
                $cableTransaction->user->setAccountBalance($amount);
                $cableTransaction->update(['status' => 2]);
            }
        }

        if (isset($this->selectedUser['electricity'])) {
            foreach (array_keys($this->selectedUser['electricity']) as $electricity) {
                $electricityTransaction = ElectricityTransaction::where('transaction_id', $electricity)->first();
                $amount = CalculateDiscount::calculate($electricityTransaction->amount, $electricityTransaction->discount);
                $electricityTransaction->user->setAccountBalance($amount);
                $electricityTransaction->update(['status' => 2]);
            }
        }

        if (isset($this->selectedUser['education'])) {
            foreach (array_keys($this->selectedUser['education']) as $education) {
                $educationTransaction = ResultCheckerTransaction::where('transaction_id', $education)->first();
                $amount = CalculateDiscount::calculate($electricityTransaction->amount, $electricityTransaction->discount);
                $educationTransaction->user->setAccountBalance($amount);
                $educationTransaction->update(['status' => 2]);
            }
        }

        $this->dispatch('success-toastr', ['message' => 'Transaction Refunded Successfully']);
        session()->flash('success', 'Transaction Refunded Successfully');
        $this->redirect(url()->previous());
    }

    public function render(Request $request)
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

        return view('livewire.admin.transaction.index', compact('transactions'));
    }
}
