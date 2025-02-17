<?php

namespace App\Models;

use App\Traits\GeneratesTransactionId;
use App\Traits\RecordsBalanceChanges;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PalmPayTransaction extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $statusField = 'api_status';

    protected static function boot()
    {
        parent::boot();
        static::creating(function($model) {
            do {
                $uuid = Str::uuid();
            } while (self::where('uuid', $uuid)->exists());

            $model->uuid = $uuid;
            $model->reference_id = self::generateUniqueReferenceId();
            $model->transaction_id = self::generateUniqueTransactionId();
        });
    }

    private static function generateUniqueReferenceId(): string
    {
        return Str::slug(date('YmdHi').'-palmpay-'.Str::random(10).Str::random(4));
    }

    private static function generateUniqueTransactionId(): string
    {
        $vendorCode = 'VST';
        $transactionNumber = Auth::id();
        $timestamp = date('YmdHis');
        $randomDigits = Str::random(6);

        return "{$vendorCode}|{$transactionNumber}|{$timestamp}|{$randomDigits}";
    }
}
