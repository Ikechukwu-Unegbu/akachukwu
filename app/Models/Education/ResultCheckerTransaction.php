<?php

namespace App\Models\Education;

use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResultCheckerTransaction extends Model
{
    use LogsActivity; 
    
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logAll();
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->user_id = auth()->user()->id;
            $model->transaction_id = static::generateUniqueId();
            $model->reference_id = static::generateReferenceId();
        });
    }

    public function result_checker_pins()
    {
        return $this->hasMany(ResultCheckerPin::class, 'result_checker_id');
    }

    public static function generateUniqueId(): string
    {
        return Str::slug(date('Ymd').microtime().'-result-checker-'.Str::random(10).Str::random(4));
    }

    public static function generateReferenceId()
    {
        $generate = date('YmdHi') . Str::random(10);
        return Str::lower($generate);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($query, $search) {
            $query->where('transaction_id', 'LIKE', "%{$search}%")
                ->orWhere('quantity', 'LIKE', "%{$search}%")
                ->orWhere('exam_name', 'LIKE', "%{$search}%")
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('username', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                });
        });
    }
}