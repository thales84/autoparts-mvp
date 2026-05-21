<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Setting;
use App\Services\Cart\CartService;
use App\Services\Orders\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService  $cart,
        private OrderService $orders,
    ) {}

    public function show(): View|RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        $items = $this->cart->getItems();
        $total = $this->cart->total();
        $user  = auth()->user();

        return view('public.checkout.show', compact('items', 'total', 'user'));
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        try {
            $order = $this->orders->createFromCart(
                auth()->user(),
                $request->validated(),
            );
        } catch (ValidationException $e) {
            return redirect()->route('cart.index')
                ->withErrors($e->errors());
        }

        return redirect()->route('account.orders.show', $order)
            ->with('success', 'Commande créée ! Téléchargez votre devis et suivez les instructions de paiement.');
    }
}
