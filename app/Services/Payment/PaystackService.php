<?php

namespace App\Services\Payment;

use App\Exceptions\PaymentInitialisationError;
use App\Interfaces\Payment\Payment;
use App\Models\Paystack;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService implements Payment
{
    public function isGatewayAvailable(): bool
    {
        return true;
    }

    public function createPaymentIntent($amount, $email, $redirectURL, array $meta = []): Collection
    {

        return collect([

        ]);
    }
 
    public function processPayment($request): bool
    {
        return false;
    }
  

   
    public function generateUniqueId(): string
    {
        return rand(1000, 99999999).str_replace(' ', '', microtime()).rand(1000, 99999999);

    }
}
