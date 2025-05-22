<?php

namespace App\Models;

use App\Helpers\GeneralHelpers;
use App\Models\Data\DataTransaction;
use Illuminate\Database\Eloquent\Model;
use App\Models\Utility\AirtimeTransaction;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScheduledTransaction extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'next_run_at' => 'datetime',
        'last_run_at' => 'datetime',
        'logs' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = GeneralHelpers::generateUniqueUuid(table: 'scheduled_transactions');
            $model->user_id = auth()->id();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function airtimeTransactions(): HasMany
    {
        return $this->hasMany(AirtimeTransaction::class);
    }

    public function dataTransactions(): HasMany
    {
        return $this->hasMany(DataTransaction::class);
    }
}
