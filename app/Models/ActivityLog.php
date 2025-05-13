<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'actor_id',
        'resource_owner_id',
        'activity',
        'resource',
        'description',
        'type',
        'raw_request',
        'raw_response',
        'ip_address',
        'user_agent',
        'location',
        'balance_before',
        'balance_after',
        'tags',
        'ref_id',
    ];

    protected $casts = [
        'raw_request' => 'array',
        'raw_response' => 'array',
        'tags' => 'array',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    // Relationships

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function resourceOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resource_owner_id');
    }

}


