<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralContest extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_ENDED = 2;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'active',
        'status',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'integer',

    ];

    // Helpers to translate between string active and integer status
    public static function statusFromActiveString(string $active): int
    {
        return match ($active) {
            'active' => self::STATUS_ACTIVE,
            'ended' => self::STATUS_ENDED,
            default => self::STATUS_PENDING,
        };
    }

    public static function activeFromStatusInt(int $status): string
    {
        return match ($status) {
            self::STATUS_ACTIVE => 'active',
            self::STATUS_ENDED => 'ended',
            default => 'pending',
        };
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeEnded($query)
    {
        return $query->where('status', self::STATUS_ENDED);
    }

    // State mutators
    public function activate(): void
    {
        $this->update([
            'active' => 'active',
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function deactivate(): void
    {
        $this->update([
            'active' => 'ended',
            'status' => self::STATUS_ENDED,
        ]);
    }
}
