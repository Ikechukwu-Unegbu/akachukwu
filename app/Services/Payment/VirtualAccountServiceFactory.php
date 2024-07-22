<?php

namespace App\Services\Payment;

use App\Models\PaymentGateway;

class VirtualAccountServiceFactory 
{
    public static function make()
    {
        $activeGateway = PaymentGateway::where('va_status', true)->first();

        if (!$activeGateway) {
            return;
        }

        switch ($activeGateway->name) {
            case 'Monnify':
                return new MonnifyService();
            case 'Payvessel':
                return new PayVesselService();
            default:
                throw new \Exception('Invalid gateway name.');
        }

    }
    
}