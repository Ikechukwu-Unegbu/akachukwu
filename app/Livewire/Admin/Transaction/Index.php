<?php

namespace App\Livewire\Admin\Transaction;

use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use App\Models\Data\DataType;
use App\Models\MoneyTransfer;
use App\Models\Data\DataNetwork;
use App\Models\Payment\Paystack;
use Illuminate\Support\Facades\DB;
use App\Models\Payment\Flutterwave;
use App\Services\CalculateDiscount;
use Illuminate\Support\Facades\Log;
use App\Models\Data\DataTransaction;
use App\Models\Utility\CableTransaction;
use App\Models\Payment\VastelTransaction;
use App\Models\Payment\MonnifyTransaction;
use App\Models\Utility\AirtimeTransaction;
use App\Services\Referrals\ReferralService;
use App\Models\Payment\PayVesselTransaction;
use App\Models\Utility\ElectricityTransaction;
use App\Services\Vendor\QueryVendorTransaction;
use App\Models\Education\ResultCheckerTransaction;
use App\Models\PalmPayTransaction;

use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;
use App\Services\Admin\Activity\ActivityLogService;
use App\Helpers\GeneralHelpers;

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
    public $error_msg;
    public $get_transaction;
    public $vendorResponse;
    public $network;
    public $networkIds;
    public $dataType;
    public $dataTypeIds;
    public $statuses = [
        'successful', 'processing', 'pending', 'failed', 'refunded', 'negative'
    ];

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

        DB::transaction(function(){
            if (isset($this->selectedUser['data'])) {
                $batchId = GeneralHelpers::generateUniqueNumericRef('activity_logs');
                foreach (array_keys($this->selectedUser['data']) as $data) {
                    $dataTransaction = DataTransaction::where('transaction_id', $data)->first();
                    $amount = CalculateDiscount::calculate($dataTransaction->amount, $dataTransaction->discount);
                    $dataTransaction->user->setAccountBalance($amount);
                    $dataTransaction->refund();
                    (new ReferralService)->reverseRferrerpay($dataTransaction);

                    ActivityLogService::log([
                        'actor_id' => auth()->id(),
                        'resource_owner' => $dataTransaction->user_id,
                        'activity' => 'data_refund',
                        'type'=>'Data Transaction',
                        'resource' => serialize($dataTransaction),
                        'description' => 'Refunded data transaction: ' . $dataTransaction->transaction_id,
                        'ref_id' => $batchId,
                        'tags' => ['refund', 'data'],
                    ]);
                }
            }
        });
       

        DB::transaction(function(){
            if (isset($this->selectedUser['airtime'])) {
                $batchId = GeneralHelpers::generateUniqueNumericRef('activity_logs');
                foreach (array_keys($this->selectedUser['airtime']) as $airtime) {
                    $airtimeTransaction = AirtimeTransaction::where('transaction_id', $airtime)->first();
                    // $amount = CalculateDiscount::calculate($airtimeTransaction->amount, $airtimeTransaction->discount);
                    $amount = $airtimeTransaction->amount;
                    $airtimeTransaction->user->setAccountBalance($amount);
                    $airtimeTransaction->refund();

                    ActivityLogService::log([
                        'actor_id' => auth()->id(),
                        'resource_owner' => $airtimeTransaction->user_id,
                        'activity' => 'airtime_refund',
                        'type'=>'Airtime Transaction',
                        'resource' => serialize($airtimeTransaction),
                        'description' => 'Refunded airtime transaction: ' . $airtimeTransaction->transaction_id,
                        'ref_id' => $batchId,
                        'tags' => ['refund', 'airtime'],
                    ]);
                }
            }
        });
     

        DB::transaction(function(){

            if (isset($this->selectedUser['cable'])) {
                $batchId = GeneralHelpers::generateUniqueNumericRef('activity_logs');
                foreach (array_keys($this->selectedUser['cable']) as $cable) {
                    $cableTransaction = CableTransaction::where('transaction_id', $cable)->first();
                    $amount = CalculateDiscount::calculate($cableTransaction->amount, $cableTransaction->discount);
                    $cableTransaction->user->setAccountBalance($amount);
                    $cableTransaction->refund();

                    ActivityLogService::log([
                        'actor_id' => auth()->id(),
                        'resource_owner' => $cableTransaction->user_id,
                        'type'=>"Cable Transaction",
                        'activity' => 'cable_refund',
                        'resource' => serialize($cableTransaction),
                        'description' => 'Refunded airtime transaction: ' . $cableTransaction->transaction_id,
                        'ref_id' => $batchId,
                        'tags' => ['refund', 'cable'],
                    ]);
                }
            }
          
        });

      
        DB::transaction(function(){
            if (isset($this->selectedUser['electricity'])) {
                $batchId = GeneralHelpers::generateUniqueNumericRef('activity_logs');
                foreach (array_keys($this->selectedUser['electricity']) as $electricity) {
                    $electricityTransaction = ElectricityTransaction::where('transaction_id', $electricity)->first();
                    $amount = CalculateDiscount::calculate($electricityTransaction->amount, $electricityTransaction->discount);
                    $electricityTransaction->user->setAccountBalance($amount);
                    $electricityTransaction->refund();

                    ActivityLogService::log([
                        'actor_id' => auth()->id(),
                        'resource_owner' => $electricityTransaction->user_id,
                        'activity' => 'electric_refund',
                        'type'=>'Electricity Transaction',
                        'resource' => serialize($electricityTransaction),
                        'description' => 'Refunded electricity transaction: ' . $electricityTransaction->transaction_id,
                        'ref_id' => $batchId,
                        'tags' => ['refund', 'electricity'],
                    ]);
                }
            }
        });

        DB::transaction(function(){
            if (isset($this->selectedUser['education'])) {
                $batchId = GeneralHelpers::generateUniqueNumericRef('activity_logs');
                foreach (array_keys($this->selectedUser['education']) as $education) {
                    $educationTransaction = ResultCheckerTransaction::where('transaction_id', $education)->first();
                    $amount = CalculateDiscount::calculate($electricityTransaction->amount, $electricityTransaction->discount);
                    $educationTransaction->user->setAccountBalance($amount);
                    $educationTransaction->refund();
                    

                    ActivityLogService::log([
                        'actor_id' => auth()->id(),
                        'resource_owner' => $educationTransaction->user_id,
                        'activity' => 'education_refund',
                        'type'=>'Eduction Transaction',
                        'resource' => serialize($educationTransaction),
                        'description' => 'Refunded education transaction: ' . $educationTransaction->transaction_id,
                        'ref_id' => $batchId,
                        'tags' => ['refund', 'education'],
                    ]);
                }
            }
        });

    

      

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
        ActivityLogService::log([
            'actor_id' => auth()->id(),
            // 'resource_owner' => $educationTransaction->user_id,
            'activity' => 'view',
            'type'=>'Transaction',
            'resource' => serialize($this->transactionDetails),
            'description' => 'Viewed Transaction with id: '.$id.' and type of: '.$type,
            // 'ref_id' => $batchId,
            'tags' => ['transation', 'read'],
        ]);
    }


    public function queryTransaction($id, $type)
    {
        $this->error_msg = "";
        $this->vendorResponse = "";
        $this->transaction_id = $id;
        $this->transaction_type = $type;
        $this->get_transaction = self::getTransactionTableByType($type, $id);
        
        $query =  QueryVendorTransaction::initializeQuery($id, $type);
        
        ActivityLogService::log([
            'actor_id' => auth()->id(),
            'activity' => 'view',
            'type'=>'Transaction',
            'description' => 'Queried Transaction with id: '.$id.' and type of: '.$type,
            'tags' => ['transation', 'read'],
        ]);
        if (!isset($query->status)) {
            $this->dispatch('error-toastr', ['message' => "Unable to query transaction. Please try again later."]);
            $this->error_msg = "Unable to query transaction. Please try again later.";
            $this->loader = false;
            return;
        } elseif (!$query->status) {
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
            
            if ($this->transaction_type === 'airtime') {
                $amount = $transaction->amount;
            }

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

            if ($this->transaction_type === 'airtime') {
                $amount = $transaction->amount;
            }
            
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
            'education'      =>  ResultCheckerTransaction::class,
            'flutterwave'    =>  Flutterwave::class,
            'paystack'       =>  Paystack::class,
            'monnify'        =>  MonnifyTransaction::class,
            'payvessel'      =>  PayVesselTransaction::class,
            'vastel'         =>  VastelTransaction::class,
            'money_transfer' => MoneyTransfer::class,
            'palmpay'        =>  PalmPayTransaction::class
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

    public function updatedNetwork()
    {
        $this->networkIds = DataNetwork::where('name', $this->network)->pluck('id')->toArray();
    }

    public function updatedDataType()
    {
        $this->dataTypeIds = DataType::where('name', $this->dataType)->pluck('id')->toArray();
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
                SELECT id, reference_id as transaction_id, user_id, amount, status, transfer_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, type, "money_transfer" as utility, "fa-exchange-alt" as icon, "Wallet Topup" as title, created_at FROM money_transfers 
            ) as transactions
        '))->join('users', 'transactions.user_id', '=', 'users.id')
            ->select('transactions.*', 'users.username as user_name')
            ->when($this->search, function ($query, $search) {
                $query->where('transaction_id', 'LIKE', "%$search%")
                ->where('subscribed_to', 'LIKE', "%$search%")
                    ->orWhere('users.name', 'LIKE', "%$search%")
                    ->orWhere('users.username', 'LIKE', "%$search%")
                    // ->orWhere('users.phone', 'LIKE', "%$search%")
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

       
        if ($this->status && $this->status !== 'negative') {
            $query->where('transactions.vendor_status', $this->status);
        }

        if ($this->status === 'negative') {
            $query->where('transactions.amount', '<', 0);
        }
        
        $transactions = $query->paginate($this->perPage);

        $networks = DataNetwork::get()->unique('name');
        
        $dataTypes = $this->network ? DataType::whereIn('network_id', $this->networkIds)->where('status', true)->get()->unique('name') : [];

        return view('livewire.admin.transaction.index', compact('transactions', 'networks', 'dataTypes'));
    }
}
