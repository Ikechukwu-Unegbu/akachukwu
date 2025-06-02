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

    public function getTitleAttribute(): string
    {
        // Handle MoneyTransfer special case
        if ($this instanceof \App\Models\MoneyTransfer) {
            $prefix = $this->type == 'internal' ? 'internal transfer' : 'bank transfer';
            return $prefix;
        }

        // Default cases for other models
        $type = str_replace('_', ' ', $this->getTable());
        $type = str_replace(' transactions', '', $type);
        $type = ucfirst($type);

        return $type;
    }

    public static function bootHasTransactionType()
    {
        static::retrieved(function ($model) {
            $appends = $model->appends ?? [];

            if (!in_array('transaction_type', $appends)) {
                $appends[] = 'transaction_type';
            }

            if (!in_array('title', $appends)) {
                $appends[] = 'title';
            }

            $model->appends = $appends;
        });
    }
}
