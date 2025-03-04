<?php

namespace App\Services\Payment;

use App\Models\PaymentGateway;
use App\Services\Money\PalmPayService;

class VirtualAccountServiceFactory 
{
    public static function make(PaymentGateway $paymentGateway)
    {
        switch ($paymentGateway->name) {
            case 'Monnify':
                return new MonnifyService();
            case 'Payvessel':
                return new PayVesselService();
            case 'Palmpay':
                return new PalmPayService();
            default:
                throw new \Exception('Invalid gateway name.');
        }

    }
    
}