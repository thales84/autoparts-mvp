<?php

namespace App\Services\Payments;

use App\Models\Order;

/**
 * Stub Stripe — à implémenter avec stripe/stripe-php si nécessaire.
 */
class StripePaymentGateway implements PaymentGatewayInterface
{
    public function createCheckout(Order $order): PaymentResult
    {
        return PaymentResult::fail('Stripe non configuré pour ce projet.');
    }

    public function handleSuccess(array $payload): PaymentResult
    {
        return PaymentResult::fail('Stripe non configuré pour ce projet.');
    }

    public function handleCancel(array $payload): PaymentResult
    {
        return PaymentResult::fail('Paiement Stripe annulé.');
    }
}
