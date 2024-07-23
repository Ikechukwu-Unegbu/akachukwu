<?php

namespace App\Services\Payment;

use App\Models\PaymentGateway;

class VirtualAccountServiceFactory 
{
    public static function make(PaymentGateway $paymentGateway)
    {
        switch ($paymentGateway->name) {
            case 'Monnify':
                return new MonnifyService();
            case 'Payvessel':
                return new PayVesselService();
            default:
                throw new \Exception('Invalid gateway name.');
        }

    }
    
}