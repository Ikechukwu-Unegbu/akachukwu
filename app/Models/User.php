<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

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

    public function isAdmin()
    {
        return auth()->user()->role == 'admin';
    }

    public function isUser()
    {
        return auth()->user()->role == 'user';
    }
}
