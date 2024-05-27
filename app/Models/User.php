<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

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
        'user_level'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', ]);
        // Chain fluent methods for configuration options
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

    public function beneficiaries() : HasMany
    {
        return $this->hasMany(Beneficiary::class);
    }

    public function virtualAccounts() : HasMany
    {
        return $this->hasMany(VirtualAccount::class);
    }

}
