<?php

namespace App\Interfaces\Payment;

use Illuminate\Support\Collection;

interface Payment
{
    /**
     * @param $amount Must be in lowest denomination of currency
     * @param  array  $meta The extra attributes to be passed along during the payment
     * @return collection collection would include the following items paymentLink, status, message
     *
     * @throws \App\Exceptions\PaymentInitialisationError
     */
    public function createPaymentIntent($amount, $redirectURL, $user, array $meta = []): Collection;

    public function isGatewayAvailable(): bool;

    public function processPayment($request): bool;

    // public function refundPayment($transactionId): bool;
}
