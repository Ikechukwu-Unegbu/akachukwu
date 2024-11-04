<?php

namespace App\Traits;

trait TransactionStatusTrait
{
    public function refund()
    {
        $this->status = 2;
        $this->vendor_response = 'pending';
        $this->save();
    }

    public function debit()
    {
        $this->status = 0;
        $this->vendor_response = 'failed';
        $this->save();
    }
}
