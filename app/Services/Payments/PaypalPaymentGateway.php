<?php

namespace App\Services\Payments;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaypalPaymentGateway implements PaymentGatewayInterface
{
    private string $clientId;
    private string $clientSecret;
    private string $baseUrl;
    private string $currency;

    public function __construct()
    {
        $this->clientId     = config('payments.paypal.client_id', '');
        $this->clientSecret = config('payments.paypal.client_secret', '');
        $this->currency     = config('payments.paypal.currency', 'USD');
        $this->baseUrl      = config('payments.paypal.mode', 'sandbox') === 'production'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    public function createCheckout(Order $order): PaymentResult
    {
        if (empty($this->clientId) || empty($this->clientSecret)) {
            return PaymentResult::fail('Identifiants PayPal non configurés.');
        }

        try {
            $token = $this->getAccessToken();

            $response = Http::withToken($token)
                ->acceptJson()
                ->post("{$this->baseUrl}/v2/checkout/orders", [
                    'intent'         => 'CAPTURE',
                    'purchase_units' => [
                        [
                            'reference_id' => $order->order_number,
                            'custom_id'    => (string) $order->id,
                            'description'  => "Commande PALERME AUTO PRO #{$order->order_number}",
                            'amount'       => [
                                'currency_code' => $this->currency,
                                'value'         => number_format((float) $order->total, 2, '.', ''),
                            ],
                        ],
                    ],
                    'application_context' => [
                        'brand_name'  => config('app.name', 'AutoParts'),
                        'locale'      => 'fr-FR',
                        'user_action' => 'PAY_NOW',
                        'return_url'  => route('checkout.success'),
                        'cancel_url'  => route('checkout.cancel'),
                    ],
                ]);

            if (! $response->successful()) {
                Log::error('PayPal createCheckout error', ['body' => $response->json()]);
                return PaymentResult::fail(
                    $response->json('message', 'Erreur PayPal lors de la création du paiement.'),
                    $response->json() ?? [],
                );
            }

            $data     = $response->json();
            $paypalId = $data['id'];
            $approve  = collect($data['links'] ?? [])->firstWhere('rel', 'approve');

            if (! $approve) {
                return PaymentResult::fail('Lien approbation PayPal introuvable.', $data);
            }

            return PaymentResult::redirect($paypalId, $approve['href'], $data);

        } catch (\Throwable $e) {
            Log::error('PayPal createCheckout exception', ['message' => $e->getMessage()]);
            return PaymentResult::fail('Erreur inattendue PayPal : ' . $e->getMessage());
        }
    }

    public function handleSuccess(array $payload): PaymentResult
    {
        // PayPal fournit ?token=PAYPAL_ORDER_ID dans l'URL de retour
        $paypalOrderId = $payload['token'] ?? null;

        if (! $paypalOrderId) {
            return PaymentResult::fail('Token PayPal manquant dans le callback.');
        }

        try {
            $token = $this->getAccessToken();

            // Capture le paiement côté serveur
            $response = Http::withToken($token)
                ->acceptJson()
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("{$this->baseUrl}/v2/checkout/orders/{$paypalOrderId}/capture", (object) []);

            if (! $response->successful()) {
                Log::error('PayPal capture error', ['body' => $response->json()]);
                return PaymentResult::fail(
                    $response->json('message', 'Capture du paiement échouée.'),
                    $response->json() ?? [],
                );
            }

            $data          = $response->json();
            $captureStatus = data_get($data, 'purchase_units.0.payments.captures.0.status');

            if ($captureStatus !== 'COMPLETED') {
                return PaymentResult::fail(
                    "Paiement non complété (statut PayPal : {$captureStatus}).",
                    $data,
                );
            }

            $captureId = data_get($data, 'purchase_units.0.payments.captures.0.id', $paypalOrderId);

            return PaymentResult::ok($captureId, $data);

        } catch (\Throwable $e) {
            Log::error('PayPal handleSuccess exception', ['message' => $e->getMessage()]);
            return PaymentResult::fail('Erreur inattendue lors de la capture : ' . $e->getMessage());
        }
    }

    public function handleCancel(array $payload): PaymentResult
    {
        return PaymentResult::fail('Paiement annulé par le client.');
    }

    private function getAccessToken(): string
    {
        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post("{$this->baseUrl}/v1/oauth2/token", ['grant_type' => 'client_credentials']);

        if (! $response->successful()) {
            throw new \RuntimeException('Authentification PayPal échouée : ' . $response->body());
        }

        return $response->json('access_token');
    }
}
