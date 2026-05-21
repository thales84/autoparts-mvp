<?php

namespace App\Services\Payments;

use InvalidArgumentException;

class PaymentGatewayResolver
{
    public function resolve(): PaymentGatewayInterface
    {
        return match (config('payments.provider', 'paypal')) {
            'paypal' => new PaypalPaymentGateway(),
            'stripe' => new StripePaymentGateway(),
            default  => throw new InvalidArgumentException(
                'Provider de paiement non supporté : ' . config('payments.provider')
            ),
        };
    }
}
