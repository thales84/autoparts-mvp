<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Cart\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index(): View
    {
        $items = $this->cart->getItems();
        $total = $this->cart->total();

        return view('public.cart.index', compact('items', 'total'));
    }

    public function add(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
        ]);

        $this->cart->add(
            (int) $request->product_id,
            (int) $request->quantity,
        );

        if ($request->expectsJson()) {
            $product = Product::findOrFail($request->product_id);

            return response()->json([
                'product' => [
                    'name'     => $product->name,
                    'price'    => number_format($product->price, 2, ',', ' '),
                    'image'    => $product->main_image_path ? asset($product->main_image_path) : null,
                    'quantity' => (int) $request->quantity,
                ],
                'cart' => [
                    'count' => $this->cart->count(),
                    'total' => number_format($this->cart->total(), 2, ',', ' '),
                ],
            ]);
        }

        return back()->with('success', 'Produit ajouté au panier.');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity'   => ['required', 'integer', 'min:0'],
        ]);

        $this->cart->update(
            (int) $request->product_id,
            (int) $request->quantity,
        );

        return back()->with('success', 'Panier mis à jour.');
    }

    public function remove(int $productId): RedirectResponse
    {
        $this->cart->remove($productId);

        return back()->with('success', 'Produit retiré du panier.');
    }

    public function clear(): RedirectResponse
    {
        $this->cart->clear();

        return back()->with('success', 'Panier vidé.');
    }
}
