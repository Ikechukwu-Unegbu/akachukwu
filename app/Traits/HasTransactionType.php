<?php

namespace App\Traits;
trait HasTransactionType
{
    public function getTransactionTypeAttribute(): string
    {
        if (property_exists($this, 'addsToBalance')) {
            return $this->addsToBalance ? 'credit' : 'debit';
        }

        if (isset($this->type)) {
            return $this->type == 1 ? 'credit' : 'debit';
        }

        return 'debit';
    }

    public static function bootHasTransactionType()
    {
        static::retrieved(function ($model) {
            if (!in_array('transaction_type', $model->appends ?? [])) {
                $model->appends[] = 'transaction_type';
            }
        });
    }
}
