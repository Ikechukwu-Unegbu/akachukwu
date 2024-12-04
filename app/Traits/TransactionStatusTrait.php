<?php

namespace App\Traits;

trait TransactionStatusTrait
{
    protected const STATUS_SUCCESS = 'successful';
    protected const STATUS_PROCESSING = 'processing';
    protected const STATUS_PENDING = 'pending';
    protected const STATUS_REFUND = 'refunded';
    protected const STATUS_FAILED = 'failed';

    public function success()
    {
        $this->status = 1;
        $this->vendor_status = self::STATUS_SUCCESS;
        $this->vendor_response = self::STATUS_SUCCESS;
        $this->save();
    }

    public function refund()
    {
        $this->status = 2;
        $this->vendor_status = self::STATUS_REFUND;
        $this->vendor_response = self::STATUS_REFUND;
        $this->save();
    }

    public function pending()
    {
        $this->status = 3;
        $this->vendor_status = self::STATUS_PENDING;
        $this->vendor_response = self::STATUS_PENDING;
        $this->save();
    }

    public function debit()
    {
        $this->status = 0;
        $this->vendor_status = self::STATUS_FAILED;
        $this->vendor_response = self::STATUS_FAILED;
        $this->save();
    }

    public function balanceAfterRefund($amount)
    {
        $this->balance_after_refund = $amount;
        $this->save();
    }
}
