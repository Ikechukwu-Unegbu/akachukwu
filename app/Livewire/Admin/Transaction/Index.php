<?php

namespace App\Livewire\Admin\Transaction;

use App\Models\Data\DataTransaction;
use App\Models\Education\ResultCheckerTransaction;
use App\Models\MoneyTransfer;
use App\Models\Payment\Flutterwave;
use App\Models\Payment\MonnifyTransaction;
use App\Models\Payment\Paystack;
use App\Models\Payment\PayVesselTransaction;
use App\Models\Payment\VastelTransaction;
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
    public $types = ['data', 'airtime', 'cable', 'electricity', 'education', 'flutterwave', 'paystack', 'monnify', 'payvessel', 'vastel', 'money_transfer'];
    public $transactionDetails;
    public $transaction_id;
    public $transaction_type;
    public $loader = true;

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

    public function handleTransaction($id, $type)
    {
        $this->transaction_id = $id;
        $this->transaction_type = $type;
        $this->loader = false;
        $this->transactionDetails = self::getTransactionTableByType($type, $id);
    }

    public function handleModal()
    {
        $this->transactionDetails = "";
        $this->loader = true;
    }

    public static function getTransactionTableByType($type, $id)
    {
        $transactionTables = [
            'airtime'        =>  AirtimeTransaction::class,
            'cable'          =>  CableTransaction::class,
            'data'           =>  DataTransaction::class,
            'electricity'    =>  ElectricityTransaction::class,
            'education'      =>  ResultCheckerTransaction::class,
            'flutterwave'    =>  Flutterwave::class,
            'paystack'       =>  Paystack::class,
            'monnify'        =>  MonnifyTransaction::class,
            'payvessel'      =>  PayVesselTransaction::class,
            'vastel'         =>  VastelTransaction::class,
            'money_transfer' => MoneyTransfer::class
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

    public function render(Request $request)
    {
        $query = DB::table(DB::raw('
            (
                SELECT id, transaction_id, user_id, amount, status, vendor_status, mobile_number as subscribed_to, plan_network as plan_name, "Phone No." as type, "data" as utility, "fa-wifi" as icon, "Data Purchased" as title, created_at FROM data_transactions
                UNION ALL
                SELECT id, transaction_id, user_id, amount, status, vendor_status, mobile_number as subscribed_to, network_name as plan_name, "Phone No." as type, "airtime" as utility, "fa-mobile-alt" as icon, "Airtime Purchased" as title, created_at FROM airtime_transactions
                UNION ALL
                SELECT id, transaction_id, user_id, amount, status, vendor_status, smart_card_number as subscribed_to, cable_name as plan_name, "IUC" as type, "cable" as utility, "fa-tv" as icon, "Cable TV Purchased" as title, created_at FROM cable_transactions
                UNION ALL
                SELECT id, transaction_id, user_id, amount, status, vendor_status, meter_number as subscribed_to, disco_name as plan_name, "Meter No." as type, "electricity" as utility, "fa-bolt" as icon, "Electricity Purchased" as title, created_at FROM electricity_transactions
                UNION ALL
                SELECT id, transaction_id, user_id, amount, status, vendor_status, quantity as subscribed_to, exam_name as plan_name, "QTY" as type, "education" as utility, "fa-credit-card" as icon, "E-PINS Purchased" as title, created_at FROM result_checker_transactions
                UNION ALL
                SELECT id, reference_id as transaction_id, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "flutterwave" as utility, "fa-exchange-alt" as icon, "Wallet Topup" as title, created_at FROM flutterwave_transactions
                UNION ALL
                SELECT id, reference_id as transaction_id, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "paystack" as utility, "fa-exchange-alt" as icon, "Wallet Topup" as title, created_at FROM paystack_transactions
                UNION ALL
                SELECT id, reference_id as transaction_id, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "monnify" as utility, "fa-exchange-alt" as icon, "Wallet Topup" as title, created_at FROM monnify_transactions
                UNION ALL
                SELECT id, reference_id as transaction_id, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "payvessel" as utility, "fa-exchange-alt" as icon, "Wallet Topup" as title, created_at FROM pay_vessel_transactions
                UNION ALL
                SELECT id, reference_id as transaction_id, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "vastel" as utility, "fa-exchange-alt" as icon, "Wallet Topup" as title, created_at FROM vastel_transactions
                UNION ALL
                SELECT id, reference_id as transaction_id, user_id, amount, status, status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "money_transfer" as utility, "fa-exchange-alt" as icon, "Wallet Topup" as title, created_at FROM money_transfers
            ) as transactions
        '))->join('users', 'transactions.user_id', '=', 'users.id')
            ->select('transactions.*', 'users.username as user_name')
            ->when($this->search, function ($query, $search) {
                $query->where('transaction_id', 'LIKE', "%$search%")
                    ->orWhere('users.name', 'LIKE', "%$search%")
                    ->orWhere('users.phone', 'LIKE', "%$search%")
                    ->orWhere('type', 'LIKE', "%$search%");
            })
            ->orderBy('transactions.created_at', 'desc');

        if ($this->type) {
            $query->where('transactions.utility', $this->type);
        }

        if ($this->startDate) {
            $query->where('transactions.created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->where('transactions.created_at', '<=', $this->endDate);
        }

       

        if (is_numeric($this->status)) {
            $query->where('transactions.status', (int) $this->status);
        }

        if ($this->status === 'negative') {
            $query->where('transactions.amount', '<', 0);
        }
        
        $transactions = $query->paginate($this->perPage);

        return view('livewire.admin.transaction.index', compact('transactions'));
    }
}
