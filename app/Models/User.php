<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
}
