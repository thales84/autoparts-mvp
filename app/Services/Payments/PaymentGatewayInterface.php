<?php

namespace App\Services\Payments;

use App\Models\Order;

interface PaymentGatewayInterface
{
    /**
     * Initie le paiement → retourne un résultat avec redirectUrl vers le prestataire.
     */
    public function createCheckout(Order $order): PaymentResult;

    /**
     * Gère le retour succès (callback) → capture et vérifie le paiement côté serveur.
     */
    public function handleSuccess(array $payload): PaymentResult;

    /**
     * Gère l'annulation du paiement.
     */
    public function handleCancel(array $payload): PaymentResult;
}
