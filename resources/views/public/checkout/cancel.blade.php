@extends('layouts.public')

@section('title', 'Paiement annulé')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5">

            <div class="text-center mb-4">
                <div style="width: 72px; height: 72px; background: #F5E8E8; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem; color: var(--ap-danger);">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <h2 class="fw-bold mb-1" style="color: var(--ap-text);">Paiement annulé</h2>
                <p class="text-muted">
                    Vous avez annulé le paiement.
                    @if($order)
                        Votre commande <strong>#{{ $order->order_number }}</strong> reste en attente — vous pouvez réessayer.
                    @endif
                </p>
            </div>

            @if($order)
                <div class="p-3 rounded mb-4 text-center"
                     style="background: var(--ap-bg-card)7ed; border: 1.5px solid #fed7aa; border-radius: var(--ap-radius);">
                    <div class="fw-bold mb-1" style="color: #92400e; font-size: .9rem;">
                        Commande #{{ $order->order_number }} conservée
                    </div>
                    <div class="text-muted" style="font-size: .82rem;">
                        Total : {{ number_format($order->total, 0, ',', ' ') }} {{ $order->currency }}
                        — statut : {{ $order->paymentStatusLabel() }}
                    </div>
                </div>
            @endif

            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('products.index') }}" class="btn btn-ap-outline-primary btn-sm px-4">
                    <i class="bi bi-grid me-1"></i>Retour au catalogue
                </a>
                @if($order)
                    <a href="{{ route('checkout.show') }}" class="btn btn-ap-accent btn-sm px-4">
                        <i class="bi bi-arrow-repeat me-1"></i>Réessayer le paiement
                    </a>
                @else
                    <a href="{{ route('cart.index') }}" class="btn btn-ap-accent btn-sm px-4">
                        <i class="bi bi-cart3 me-1"></i>Retour au panier
                    </a>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
