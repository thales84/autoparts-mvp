<?php

namespace App\Services\Cart;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    private const SESSION_KEY = 'cart';

    public function add(int $productId, int $quantity): void
    {
        $cart = $this->raw();
        $cart[$productId] = ($cart[$productId] ?? 0) + max(1, $quantity);
        session()->put(self::SESSION_KEY, $cart);
    }

    public function update(int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->remove($productId);
            return;
        }

        $cart = $this->raw();
        if (isset($cart[$productId])) {
            $cart[$productId] = $quantity;
            session()->put(self::SESSION_KEY, $cart);
        }
    }

    public function remove(int $productId): void
    {
        $cart = $this->raw();
        unset($cart[$productId]);
        session()->put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function isEmpty(): bool
    {
        return empty($this->raw());
    }

    public function count(): int
    {
        return count($this->raw());
    }

    /**
     * Retourne les lignes du panier avec produits chargés depuis la DB.
     * Structure : Collection of objects {product, quantity, line_total}
     */
    public function getItems(): Collection
    {
        $raw = $this->raw();

        if (empty($raw)) {
            return collect();
        }

        $productIds = array_keys($raw);

        $products = Product::with('category')
            ->where('status', 'active')
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $items = collect();

        foreach ($raw as $productId => $quantity) {
            $product = $products->get($productId);

            if (! $product) {
                continue;
            }

            $quantity = max(1, (int) $quantity);

            $items->push((object) [
                'product'    => $product,
                'quantity'   => $quantity,
                'unit_price' => (float) $product->price,
                'line_total' => (float) $product->price * $quantity,
            ]);
        }

        return $items;
    }

    public function total(): float
    {
        return $this->getItems()->sum('line_total');
    }

    /** Retourne le contenu brut de la session : [product_id => quantity] */
    public function raw(): array
    {
        return session()->get(self::SESSION_KEY, []);
    }
}
