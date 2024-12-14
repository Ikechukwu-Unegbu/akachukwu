<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Data\DataTransaction;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Utility\UpgradeRequest;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Permission;
// use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    use LogsActivity; 

    protected $fillable = [
        'name',
        'username',
        'email',
        'role',
        'email_verified_at',
        'password',
        'image',
        'address',
        'mobile',
        'referer_username',
        'gender',
        'account_balance',
        'wallet_balance',
        'bonus_balance',
        'remember_token',
        'phone',
        'user_level',
        'pin',
        'bvn',
        'nin',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', ]);
        // Chain fluent methods for configuration options
    }

    public function upgradeRequests()
    {
        return $this->hasMany(UpgradeRequest::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pin'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function setAccountBalance($amount)
    {
        $this->account_balance = $this->account_balance + $amount;
        $this->save();
    }

    public function setTransaction($amount)
    {
        $this->account_balance = $this->account_balance - $amount;
        $this->save();
    }

    public function dashboard()
    {
        if (auth()->check()) {
            if (auth()->user()->role == 'user') return route('dashboard');
            if (auth()->user()->role == 'admin') return route('admin.dashboard');
            if (auth()->user()->role == 'superadmin') return route('admin.dashboard');
        }        
    }

    public function isSuperAdmin()
    {
        return auth()->user()->role == 'superadmin';
    }

    public function referralsMade()
    {
        return $this->hasMany(Referral::class, 'referrer_id');//->with('referredUser');
    }

    public function referralsReceived()
    {
        return $this->hasMany(Referral::class, 'referred_user_id');
    }

    public function getReferredUsersWithEarnings()
    {
        return $this->referralsMade->map(function($ref) {
            return [
                'user' => User::where('id', $ref->referred_user_id)->select('name', 'username', 'phone', 'created_at')->first(),
                'referrerEarning' => $this->referrerEarning($ref->id)
            ];
        });
    }

    public function referrerEarning($userId)
    {
        $totalReferralPay = DataTransaction::where('user_id', $userId)->sum('referral_pay');
    
        return $totalReferralPay ?? 0;
    }
    

    public function isAdmin()
    {
        return auth()->user()->role == 'admin';
    }

    public function isUser()
    {
        return auth()->user()->role == 'user';
    }

    public function isReseller()
    {
        return auth()->user()->user_level == 'reseller';
    }

    public function getProfilePictureAttribute()
    {
        if ($this->image) {
            return $this->image;
        }

        $nameParts = explode(' ', $this->name);
        $alias = substr($nameParts[0], 0, 1);
        if (isset($nameParts[1])) {
            $alias .= substr($nameParts[1], 0, 1);
        }
        return "https://via.placeholder.com/90/FF0000/FFFFFF/?text={$alias}";
        // $firstLetter = strtoupper(substr($this->username, 0, 1));
        // return "https://via.placeholder.com/50/3498db/FFFFFF/?text={$firstLetter}";
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($query, $search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('username', 'LIKE', "%{$search}%")
                ->orWhere('mobile', 'LIKE', "%{$search}%");
        });
    }

    public function profileRoute()
    {
        return route('admin.hr.user.show', $this->username);
    }

    protected function hasPermission($permission)
    {
        return (bool) $this->permissions->where('name', $permission)->count();
    }

    public function hasPermissionTo($permission)
    {
        return $this->hasPermission($permission);
    }

    public function assignSuperAdminRole() : bool
    {
        $this->role = 'superadmin';
        $this->save();
        return true;
    }

    public function transformFromSuperAdminToUser() : bool
    {
        $this->role = 'user';
        $this->save();
        return true;
    }

    public function beneficiaries() : HasMany
    {
        return $this->hasMany(Beneficiary::class);
    }

    public function virtualAccounts() : HasMany
    {
        return $this->hasMany(VirtualAccount::class);
    }

    public function otp()
    {
        return $this->belongsTo(Otp::class);
    }

    public function otpVerified()
    {
        if ($this->otp && $this->otp->status !== 'unused') {
            return false;
        }

        return true;
    }
    public function walletHistories()
    {
        $transactions = DB::table('flutterwave_transactions')
            ->select('id', 'reference_id', 'amount', 'status', 'api_status', 'created_at', DB::raw("'flutter' as gateway_type"), DB::raw("NULL as type"))
            ->where('user_id', $this->id)
            ->latest();

        $transactions->union(DB::table('paystack_transactions')
            ->select('id', 'reference_id', 'amount', 'status', 'api_status', 'created_at', DB::raw("'paystack' as gateway_type"), DB::raw("NULL as type"))
            ->where('user_id', $this->id))
            ->latest();

        $transactions->union(DB::table('monnify_transactions')
            ->select('id', 'reference_id', 'amount', 'status', 'api_status', 'created_at', DB::raw("'monnify' as gateway_type"), DB::raw("NULL as type"))
            ->where('user_id', $this->id))
            ->latest();

        $transactions->union(DB::table('pay_vessel_transactions')
            ->select('id', 'reference_id', 'amount', 'status', 'api_status', 'created_at', DB::raw("'pay vessel' as gateway_type"), DB::raw("NULL as type"))
            ->where('user_id', $this->id))
            ->latest();
        
        $transactions->union(DB::table('vastel_transactions')
            ->select('id', 'reference_id', 'amount', 'status', 'api_status', 'created_at', DB::raw("'Vastel' as gateway_type"), 'type')
            ->where('user_id', $this->id))
            ->latest();

        return $transactions; // Don't forget to actually execute the query
    }


    public function isKycDone()
    {
        $user = auth()->user(); 
        return !is_null($user->nin) || !is_null($user->bvn);
    }

    // Impersonate a user
    public function impersonate(User $user)
    {
        // Store the original user ID in the session if not already stored
        if (!Session::has('superadmin')) {
            Session::put('superadmin', auth()->id());
        }

        // Switch to the impersonated user
        Auth::loginUsingId($user->id, true);
        Session::put('impersonate', $user->id);
    }

    // Stop impersonating and return to the original user
    public function stopImpersonating()
    {
        // Retrieve the original user ID from the session
        if (Session::has('superadmin')) {
            $originalUserId = Session::get('superadmin');
            
            // Switch back to the original user
            Auth::loginUsingId($originalUserId);
            
            // Clear the impersonation data from the session
            Session::forget('impersonate');
            Session::forget('superadmin');
        }
    }



    // Check if the current user is impersonating another user
    public function isImpersonating()
    {
        return Session::has('impersonate');
    }

    // public function transactionHistories($perPage = 5, $utility = '', $date = null)
    // {
    //     $query = DB::table(DB::raw('
    //         (
    //             SELECT id, transaction_id, balance_before, balance_after, user_id,  amount, status, vendor_status, mobile_number as subscribed_to, plan_network as plan_name, "Phone No." as type, "data" as utility, "fa-wifi" as icon, "Data Purchased" as title, created_at FROM data_transactions
    //             UNION ALL
    //             SELECT id, transaction_id, balance_before, balance_after, user_id,  amount, status, vendor_status, mobile_number as subscribed_to, network_name as plan_name, "Phone No." as type, "airtime" as utility, "fa-mobile-alt" as icon, "Airtime Purchased" as title, created_at FROM airtime_transactions
    //             UNION ALL
    //             SELECT id, transaction_id, balance_before, balance_after, user_id,  amount, status, vendor_status, smart_card_number as subscribed_to, cable_name as plan_name, "IUC" as type, "cable" as utility, "fa-tv" as icon, "Cable TV Purchased" as title, created_at FROM cable_transactions
    //             UNION ALL
    //             SELECT id, transaction_id, balance_before, balance_after, user_id,  amount, status, vendor_status, meter_number as subscribed_to, disco_name as plan_name, "Meter No." as type, "electricity" as utility, "fa-bolt" as icon, "Electricity Purchased" as title, created_at FROM electricity_transactions
    //             UNION ALL
    //             SELECT id, transaction_id, balance_before, balance_after, user_id,  amount, status, vendor_status, quantity as subscribed_to, exam_name as plan_name, "QTY" as type, "education" as utility, "fa-credit-card" as icon, "E-PINS Purchased" as title, created_at FROM result_checker_transactions
    //             UNION ALL
    //             SELECT id, reference_id as transaction_id, "N/A" as balance_before,  "N/A" as balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "flutterwave" as utility, "fa-exchange-alt" as icon, "Wallet Topup" as title, created_at FROM flutterwave_transactions
    //             UNION ALL
    //             SELECT id, reference_id as transaction_id, "N/A" as balance_before,  "N/A" as balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "paystack" as utility, "fa-exchange-alt" as icon, "Wallet Topup" as title, created_at FROM paystack_transactions
    //             UNION ALL
    //             SELECT id, reference_id as transaction_id, "N/A" as balance_before, "N/A" as balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "monnify" as utility, "fa-exchange-alt" as icon, "Wallet Topup" as title, created_at FROM monnify_transactions
    //             UNION ALL
    //             SELECT id, reference_id as transaction_id, "N/A" as balance_before, "N/A" as balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "payvessel" as utility, "fa-exchange-alt" as icon, "Wallet Topup" as title, created_at FROM pay_vessel_transactions
    //             UNION ALL
    //             SELECT id, reference_id as transaction_id, "N/A" as balance_before, "N/A" as balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "vastel" as utility, "fa-exchange-alt" as icon, "Wallet Topup" as title, created_at FROM vastel_transactions
    //             UNION ALL
    //             SELECT id, reference_id as transaction_id, "N/A" as balance_before, "N/A" as balance_after, user_id, amount, status, "N/A" as api_status , "wallet" as subscribed_to, reference_id, type, "transfer" as utility, "fa-exchange-alt" as icon, "Wallet Topup" as title, created_at FROM money_transfers
    //         ) as transactions
    //     '))->where('transactions.user_id', '=', $this->id)->orderBy('transactions.created_at', 'desc');
        
    //     if ($utility) $query->where('transactions.utility', $utility)->orWhere('transactions.type', $utility);
    //     if ($date) $query->whereRaw("DATE_FORMAT(transactions.created_at, '%Y-%m') = ?", $date);

    //     return $query->paginate($perPage);
    // }

    public function transactionHistories($perPage = 5, $utility = '', $date = null)
    {
        $query = DB::table(DB::raw('
            (
                SELECT id, transaction_id, balance_before, balance_after, user_id, amount, status, vendor_status, mobile_number as subscribed_to, plan_network as plan_name, "Phone No." as type, "data" as utility, "fa-wifi" as icon, "Data Purchased" as title, created_at FROM data_transactions
                UNION ALL
                SELECT id, transaction_id, balance_before, balance_after, user_id, amount, status, vendor_status, mobile_number as subscribed_to, network_name as plan_name, "Phone No." as type, "airtime" as utility, "fa-mobile-alt" as icon, "Airtime Purchased" as title, created_at FROM airtime_transactions
                UNION ALL
                SELECT id, transaction_id, balance_before, balance_after, user_id, amount, status, vendor_status, smart_card_number as subscribed_to, cable_name as plan_name, "IUC" as type, "cable" as utility, "fa-tv" as icon, "Cable TV Purchased" as title, created_at FROM cable_transactions
                UNION ALL
                SELECT id, transaction_id, balance_before, balance_after, user_id, amount, status, vendor_status, meter_number as subscribed_to, disco_name as plan_name, "Meter No." as type, "electricity" as utility, "fa-bolt" as icon, "Electricity Purchased" as title, created_at FROM electricity_transactions
                UNION ALL
                SELECT id, transaction_id, balance_before, balance_after, user_id, amount, status, vendor_status, quantity as subscribed_to, exam_name as plan_name, "QTY" as type, "education" as utility, "fa-credit-card" as icon, "E-PINS Purchased" as title, created_at FROM result_checker_transactions
                UNION ALL
                SELECT id, reference_id as transaction_id, "N/A" as balance_before, "N/A" as balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "flutterwave" as utility, "fa-exchange-alt" as icon, "Wallet Topup" as title, created_at FROM flutterwave_transactions
                UNION ALL
                SELECT id, reference_id as transaction_id, "N/A" as balance_before, "N/A" as balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "paystack" as utility, "fa-exchange-alt" as icon, "Wallet Topup" as title, created_at FROM paystack_transactions
                UNION ALL
                SELECT id, reference_id as transaction_id, "N/A" as balance_before, "N/A" as balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "monnify" as utility, "fa-exchange-alt" as icon, "Wallet Topup" as title, created_at FROM monnify_transactions
                UNION ALL
                SELECT id, reference_id as transaction_id, "N/A" as balance_before, "N/A" as balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "payvessel" as utility, "fa-exchange-alt" as icon, "Wallet Topup" as title, created_at FROM pay_vessel_transactions
                UNION ALL
                SELECT id, reference_id as transaction_id, "N/A" as balance_before, "N/A" as balance_after, user_id, amount, status, api_status as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, "funding" as type, "vastel" as utility, "fa-exchange-alt" as icon, "Wallet Topup" as title, created_at FROM vastel_transactions
                UNION ALL
                SELECT id, reference_id as transaction_id, "N/A" as balance_before, "N/A" as balance_after, user_id, amount, status, "N/A" as vendor_status, "wallet" as subscribed_to, reference_id as plan_name, type, "transfer" as utility, "fa-exchange-alt" as icon, "Money Transfer" as title, created_at 
                FROM money_transfers 
                WHERE user_id = ' . Auth::user()->id  . '
            ) as transactions
        '))->orderBy('transactions.created_at', 'desc');

        if ($utility) $query->where('transactions.utility', $utility)->orWhere('transactions.type', $utility);
        if ($date) $query->whereRaw("DATE_FORMAT(transactions.created_at, '%Y-%m') = ?", $date);

        return $query->paginate($perPage);
    }

    // public function checkUserTransactionHistories($perPage = 5, $userId, $utility = '', $date = null)
    // {
    //     $transactions = DB::table('data_transactions')
    //         ->select([
    //             'id', 'transaction_id', 'balance_before', 'balance_after', 'user_id', 'amount', 
    //             'status', 'vendor_status', 'mobile_number as subscribed_to', 'plan_network as plan_name', 
    //             DB::raw('"Phone No." as type'), DB::raw('"data" as utility'), DB::raw('"fa-wifi" as icon'), 
    //             DB::raw('"Data Purchased" as title'), 'created_at'
    //         ])
    //         ->unionAll(
    //             DB::table('airtime_transactions')
    //                 ->select([
    //                     'id', 'transaction_id', 'balance_before', 'balance_after', 'user_id', 'amount', 
    //                     'status', 'vendor_status', 'mobile_number as subscribed_to', 'network_name as plan_name', 
    //                     DB::raw('"Phone No." as type'), DB::raw('"airtime" as utility'), DB::raw('"fa-mobile-alt" as icon'), 
    //                     DB::raw('"Airtime Purchased" as title'), 'created_at'
    //                 ])
    //         )
    //         ->unionAll(
    //             DB::table('cable_transactions')
    //                 ->select([
    //                     'id', 'transaction_id', 'balance_before', 'balance_after', 'user_id', 'amount', 
    //                     'status', 'vendor_status', 'smart_card_number as subscribed_to', 'cable_name as plan_name', 
    //                     DB::raw('"IUC" as type'), DB::raw('"cable" as utility'), DB::raw('"fa-tv" as icon'), 
    //                     DB::raw('"Cable TV Purchased" as title'), 'created_at'
    //                 ])
    //         )
    //         ->unionAll(
    //             DB::table('electricity_transactions')
    //                 ->select([
    //                     'id', 'transaction_id', 'balance_before', 'balance_after', 'user_id', 'amount', 
    //                     'status', 'vendor_status', 'meter_number as subscribed_to', 'disco_name as plan_name', 
    //                     DB::raw('"Meter No." as type'), DB::raw('"electricity" as utility'), DB::raw('"fa-bolt" as icon'), 
    //                     DB::raw('"Electricity Purchased" as title'), 'created_at'
    //                 ])
    //         )
    //         ->unionAll(
    //             DB::table('result_checker_transactions')
    //                 ->select([
    //                     'id', 'transaction_id', 'balance_before', 'balance_after', 'user_id', 'amount', 
    //                     'status', 'vendor_status', 'quantity as subscribed_to', 'exam_name as plan_name', 
    //                     DB::raw('"QTY" as type'), DB::raw('"education" as utility'), DB::raw('"fa-credit-card" as icon'), 
    //                     DB::raw('"E-PINS Purchased" as title'), 'created_at'
    //                 ])
    //         )
    //         ->unionAll(
    //             DB::table('flutterwave_transactions')
    //                 ->select([
    //                     'id', 'reference_id as transaction_id', DB::raw('"N/A" as balance_before'), 
    //                     DB::raw('"N/A" as balance_after'), 'user_id', 'amount', 'status', 
    //                     'api_status as vendor_status', DB::raw('"wallet" as subscribed_to'), 'reference_id as plan_name', 
    //                     DB::raw('"funding" as type'), DB::raw('"flutterwave" as utility'), DB::raw('"fa-exchange-alt" as icon'), 
    //                     DB::raw('"Wallet Topup" as title'), 'created_at'
    //                 ])
    //         )
    //         ->unionAll(
    //             DB::table('paystack_transactions')
    //                 ->select([
    //                     'id', 'reference_id as transaction_id', DB::raw('"N/A" as balance_before'), 
    //                     DB::raw('"N/A" as balance_after'), 'user_id', 'amount', 'status', 
    //                     'api_status as vendor_status', DB::raw('"wallet" as subscribed_to'), 'reference_id as plan_name', 
    //                     DB::raw('"funding" as type'), DB::raw('"paystack" as utility'), DB::raw('"fa-exchange-alt" as icon'), 
    //                     DB::raw('"Wallet Topup" as title'), 'created_at'
    //                 ])
    //         )
    //         ->unionAll(
    //             DB::table('monnify_transactions')
    //                 ->select([
    //                     'id', 'reference_id as transaction_id', DB::raw('"N/A" as balance_before'), 
    //                     DB::raw('"N/A" as balance_after'), 'user_id', 'amount', 'status', 
    //                     'api_status as vendor_status', DB::raw('"wallet" as subscribed_to'), 'reference_id as plan_name', 
    //                     DB::raw('"funding" as type'), DB::raw('"monnify" as utility'), DB::raw('"fa-exchange-alt" as icon'), 
    //                     DB::raw('"Wallet Topup" as title'), 'created_at'
    //                 ])
    //         )
    //         ->unionAll(
    //             DB::table('pay_vessel_transactions')
    //                 ->select([
    //                     'id', 'reference_id as transaction_id', DB::raw('"N/A" as balance_before'), 
    //                     DB::raw('"N/A" as balance_after'), 'user_id', 'amount', 'status', 
    //                     'api_status as vendor_status', DB::raw('"wallet" as subscribed_to'), 'reference_id as plan_name', 
    //                     DB::raw('"funding" as type'), DB::raw('"payvessel" as utility'), DB::raw('"fa-exchange-alt" as icon'), 
    //                     DB::raw('"Wallet Topup" as title'), 'created_at'
    //                 ])
    //         )
    //         ->unionAll(
    //             DB::table('vastel_transactions')
    //                 ->select([
    //                     'id', 'reference_id as transaction_id', DB::raw('"N/A" as balance_before'), 
    //                     DB::raw('"N/A" as balance_after'), 'user_id', 'amount', 'status', 
    //                     'api_status as vendor_status', DB::raw('"wallet" as subscribed_to'), 'reference_id as plan_name', 
    //                     DB::raw('"funding" as type'), DB::raw('"vastel" as utility'), DB::raw('"fa-exchange-alt" as icon'), 
    //                     DB::raw('"Wallet Topup" as title'), 'created_at'
    //                 ])
    //         );
    
    //     // Add additional unions for other tables as needed...
    //     $query = DB::table(DB::raw("({$transactions->toSql()}) as transactions"))
    //         ->mergeBindings($transactions) // Merge bindings to ensure Laravel handles it properly
    //         ->where('transactions.user_id', $userId)
    //         ->orderBy('transactions.created_at', 'desc');
    
    //     if (!empty($utility)) {
    //         $query->where(function ($subQuery) use ($utility) {
    //             $subQuery->where('transactions.utility', $utility)
    //                 ->orWhere('transactions.type', $utility);
    //         });
    //     }
    
    //     if (!empty($date)) {
    //         $query->whereRaw("DATE_FORMAT(transactions.created_at, '%Y-%m') = ?", [$date]);
    //     }
    
    //     return $query->paginate($perPage);
    // }

    public function checkUserTransactionHistories($perPage = 5, $userId, $utility = '', $date = null)
    {
        // Base query for data transactions
        $transactions = DB::table('data_transactions')
            ->select([
                'id', 'transaction_id', 'balance_before', 'balance_after', 'user_id', 'amount',
                'status', 'vendor_status', 'mobile_number as subscribed_to', 'plan_network as plan_name',
                DB::raw('"Phone No." as type'), DB::raw('"data" as utility'), DB::raw('"fa-wifi" as icon'),
                DB::raw('"Data Purchased" as title'), 'created_at'
            ])
            ->unionAll(
                DB::table('airtime_transactions')
                    ->select([
                        'id', 'transaction_id', 'balance_before', 'balance_after', 'user_id', 'amount',
                        'status', 'vendor_status', 'mobile_number as subscribed_to', 'network_name as plan_name',
                        DB::raw('"Phone No." as type'), DB::raw('"airtime" as utility'), DB::raw('"fa-mobile-alt" as icon'),
                        DB::raw('"Airtime Purchased" as title'), 'created_at'
                    ])
            )
            ->unionAll(
                DB::table('money_transfers')
                    ->select([
                        'id', 
                        DB::raw('"N/A" as transaction_id'), 
                        DB::raw('"N/A" as balance_before'), 
                        DB::raw('"N/A" as balance_after'), 
                        'user_id', 'amount', 'status', 
                        DB::raw('"N/A" as vendor_status'), 
                        DB::raw('IF(user_id = ' . (int)$userId . ' OR recipient = ' . (int)$userId . ', recipient, user_id) as subscribed_to'),
                        DB::raw('"Transfer" as plan_name'), 
                        DB::raw('"user" as type'), 
                        DB::raw('"transfer" as utility'), 
                        DB::raw('"fa-exchange-alt" as icon'), 
                        DB::raw('"Money Transfer" as title'), 
                        'created_at'
                    ])
                    ->where(function ($query) use ($userId) {
                        $query->where('user_id', $userId)
                              ->orWhere('recipient', $userId);
                    })
            )
            ->unionAll(
                DB::table('cable_transactions')
                    ->select([
                        'id', 'transaction_id', 'balance_before', 'balance_after', 'user_id', 'amount',
                        'status', 'vendor_status', 'smart_card_number as subscribed_to', 'cable_name as plan_name',
                        DB::raw('"IUC" as type'), DB::raw('"cable" as utility'), DB::raw('"fa-tv" as icon'),
                        DB::raw('"Cable TV Purchased" as title'), 'created_at'
                    ])
            );
    
        // Wrapping the union query
        $query = DB::table(DB::raw("({$transactions->toSql()}) as transactions"))
            ->mergeBindings($transactions)
            ->where(function ($query) use ($userId) {
                $query->where('transactions.user_id', $userId)
                      ->orWhere('transactions.subscribed_to', $userId);
            })
            ->orderBy('transactions.created_at', 'desc');
    
        // Apply utility filter if provided
        if (!empty($utility)) {
            $query->where(function ($subQuery) use ($utility) {
                $subQuery->where('transactions.utility', $utility)
                    ->orWhere('transactions.type', $utility);
            });
        }
    
        // Apply date filter if provided
        if (!empty($date)) {
            $query->whereRaw("DATE_FORMAT(transactions.created_at, '%Y-%m') = ?", [$date]);
        }
    
        // Return paginated results
        return $query->paginate($perPage);
    }
    

}
