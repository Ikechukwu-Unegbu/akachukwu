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
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', ]);
        // Chain fluent methods for configuration options
    }

    public function ugradeRequests()
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
                'user' => User::where('id', $ref->referred_user_id)->select('name', 'username')->first(),
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
        if ($this->image && Storage::disk('avatars')->exists($this->image)) {
            return Storage::disk('avatars')->url($this->image);
        }

        $firstLetter = strtoupper(substr($this->username, 0, 1));
        return "https://via.placeholder.com/50/3498db/FFFFFF/?text={$firstLetter}";
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

    // public function walletHistories()
    // {
    //     $transactions = DB::table('flutterwave_transactions')
    //         ->select('id', 'reference_id', 'amount', 'status', 'created_at', DB::raw("'flutter' as gateway_type"))
    //         ->where('user_id', $this->id)
    //         ->latest();

    //     $transactions->union(DB::table('paystack_transactions')
    //         ->select('id', 'reference_id', 'amount', 'status', 'created_at', DB::raw("'paystack' as gateway_type"))
    //         ->where('user_id', $this->id))
    //         ->latest();

    //     $transactions->union(DB::table('monnify_transactions')
    //         ->select('id', 'reference_id', 'amount', 'status', 'created_at', DB::raw("'monnify' as gateway_type"))
    //         ->where('user_id', $this->id))
    //         ->latest();

    //     $transactions->union(DB::table('pay_vessel_transactions')
    //         ->select('id', 'reference_id', 'amount', 'status', 'created_at', DB::raw("'pay vessel' as gateway_type"))
    //         ->where('user_id', $this->id))
    //         ->latest();
        
    //     $transactions->union(DB::table('vastel_transactions')
    //     ->select('id', 'reference_id', 'amount', 'status', 'created_at', DB::raw("'Vastel' as gateway_type"))
    //     ->where('user_id', $this->id))
    //     ->latest();

    //     return $transactions;
    // }

    public function walletHistories()
    {
        $transactions = DB::table('flutterwave_transactions')
            ->select('id', 'reference_id', 'amount', 'status', 'created_at', DB::raw("'flutter' as gateway_type"), DB::raw("NULL as type"))
            ->where('user_id', $this->id)
            ->latest();

        $transactions->union(DB::table('paystack_transactions')
            ->select('id', 'reference_id', 'amount', 'status', 'created_at', DB::raw("'paystack' as gateway_type"), DB::raw("NULL as type"))
            ->where('user_id', $this->id))
            ->latest();

        $transactions->union(DB::table('monnify_transactions')
            ->select('id', 'reference_id', 'amount', 'status', 'created_at', DB::raw("'monnify' as gateway_type"), DB::raw("NULL as type"))
            ->where('user_id', $this->id))
            ->latest();

        $transactions->union(DB::table('pay_vessel_transactions')
            ->select('id', 'reference_id', 'amount', 'status', 'created_at', DB::raw("'pay vessel' as gateway_type"), DB::raw("NULL as type"))
            ->where('user_id', $this->id))
            ->latest();
        
        $transactions->union(DB::table('vastel_transactions')
            ->select('id', 'reference_id', 'amount', 'status', 'created_at', DB::raw("'Vastel' as gateway_type"), 'type')
            ->where('user_id', $this->id))
            ->latest();

        return $transactions; // Don't forget to actually execute the query
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
}
