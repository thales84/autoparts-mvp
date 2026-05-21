<?php

namespace App\Services\Orders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\Cart\CartService;
use App\Services\Payments\PaymentResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(private CartService $cart) {}

    /**
     * Crée une commande pending/unpaid depuis le panier en session.
     *
     * @throws ValidationException si panier vide ou stock insuffisant
     */
    public function createFromCart(User $user, array $data): Order
    {
        if ($this->cart->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => ['Votre panier est vide.'],
            ]);
        }

        return DB::transaction(function () use ($user, $data) {

            $items      = $this->cart->getItems();
            $subtotal   = 0.0;
            $currency   = 'EUR';
            $lineItems  = [];

            foreach ($items as $line) {
                $product = Product::lockForUpdate()->find($line->product->id);

                if (! $product || $product->status !== 'active') {
                    throw ValidationException::withMessages([
                        'cart' => ["Le produit « {$line->product->name} » n'est plus disponible."],
                    ]);
                }

                if ($product->stock_quantity < $line->quantity) {
                    throw ValidationException::withMessages([
                        'cart' => ["Stock insuffisant pour « {$product->name} » (disponible : {$product->stock_quantity})."],
                    ]);
                }

                $unitPrice = (float) $product->price;
                $lineTotal = $unitPrice * $line->quantity;
                $subtotal += $lineTotal;
                $currency  = $product->currency;

                $lineItems[] = [
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'product_sku'  => $product->sku,
                    'unit_price'   => $unitPrice,
                    'quantity'     => $line->quantity,
                    'line_total'   => $lineTotal,
                ];
            }

            $order = Order::create([
                'order_number'    => $this->generateOrderNumber(),
                'user_id'         => $user->id,
                'status'          => 'pending',
                'payment_status'  => 'unpaid',
                'subtotal'        => $subtotal,
                'delivery_fee'    => 0,
                'tax_amount'      => 0,
                'total'           => $subtotal,
                'currency'        => $currency,
                'customer_name'   => $data['customer_name'],
                'customer_email'  => $data['customer_email'],
                'customer_phone'  => $data['customer_phone'] ?? null,
                'delivery_address'=> $data['delivery_address'] ?? null,
                'notes'           => $data['notes'] ?? null,
            ]);

            foreach ($lineItems as $item) {
                $order->items()->create($item);
            }

            $this->cart->clear();

            return $order;
        });
    }

    /**
     * Confirme le paiement : met à jour la commande, crée le Payment, décrémente le stock.
     * Doit être appelé uniquement après capture vérifiée côté serveur.
     */
    public function confirmPayment(Order $order, PaymentResult $result): void
    {
        DB::transaction(function () use ($order, $result) {

            $order->update([
                'payment_status'   => 'paid',
                'status'           => 'confirmed',
                'payment_provider' => config('payments.provider'),
                'payment_reference'=> $result->transactionId,
            ]);

            $order->payments()->create([
                'provider'            => config('payments.provider'),
                'provider_payment_id' => $result->transactionId,
                'amount'              => $order->total,
                'currency'            => $order->currency,
                'status'              => 'paid',
                'raw_payload'         => $result->rawResponse,
                'paid_at'             => now(),
            ]);

            // Décrémentation stock uniquement après paiement confirmé
            $order->items()->with('product')->get()->each(function ($item) {
                if ($item->product) {
                    $item->product->decrement('stock_quantity', $item->quantity);
                }
            });
        });
    }

    private function generateOrderNumber(): string
    {
        return 'AP-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }
}
