<?php

namespace App\Traits;

trait TransactionStatusTrait
{
    public function success()
    {
        $this->status = 1;
        $this->vendor_status = 'successful';
        $this->save();
    }

    public function refund()
    {
        $this->status = 2;
        $this->vendor_status = 'refunded';
        $this->save();
    }

    public function pending()
    {
        $this->status = 3;
        $this->vendor_status = 'pending';
        $this->save();
    }

    public function debit()
    {
        $this->status = 0;
        $this->vendor_status = 'failed';
        $this->save();
    }
}
