@extends('layouts.public')

@section('title', 'Paiement confirmé')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            {{-- Succès icon --}}
            <div class="text-center mb-4">
                <div style="width: 80px; height: 80px; background: #E8F4EC; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2.2rem; color: var(--ap-success);">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h2 class="fw-bold mb-1" style="color: var(--ap-text);">Paiement confirmé !</h2>
                <p class="text-muted">
                    Votre commande <strong>#{{ $order->order_number }}</strong> a été payée et confirmée.
                    Merci pour votre achat.
                </p>
            </div>

            {{-- Détails commande --}}
            <div style="background: var(--ap-bg-card); border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden; margin-bottom: 1.5rem;">

                <div class="px-4 py-3 d-flex justify-content-between align-items-center"
                     style="background: #E8F4EC; border-bottom: 1px solid #B8DEC5;">
                    <span class="fw-bold" style="color: #14532d; font-size: .88rem;">
                        Commande #{{ $order->order_number }}
                    </span>
                    <div class="d-flex gap-2">
                        <span class="ap-badge ap-badge-stock">{{ $order->statusLabel() }}</span>
                        <span class="ap-badge ap-badge-stock">{{ $order->paymentStatusLabel() }}</span>
                    </div>
                </div>

                <div class="p-4">
                    @foreach($order->items as $item)
                        <div class="d-flex justify-content-between mb-2" style="font-size: .9rem;">
                            <span class="text-muted">{{ $item->product_name }} × {{ $item->quantity }}</span>
                            <span class="fw-semibold">{{ number_format($item->line_total, 0, ',', ' ') }}</span>
                        </div>
                    @endforeach

                    <hr style="border-color: var(--ap-border);">

                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total payé</span>
                        <span class="fw-bold" style="font-size: 1.05rem; color: var(--ap-success);">
                            {{ number_format($order->total, 0, ',', ' ') }} {{ $order->currency }}
                        </span>
                    </div>
                </div>

                @if($order->payment_reference)
                    <div class="px-4 pb-3" style="font-size: .78rem; color: var(--ap-text-muted);">
                        <i class="bi bi-receipt me-1"></i>Référence paiement : <code>{{ $order->payment_reference }}</code>
                    </div>
                @endif
            </div>

            {{-- Infos client --}}
            <div class="p-3 rounded mb-4" style="background: #EDE8DF; border: 1px solid var(--ap-border); font-size: .85rem;">
                <div class="row g-2">
                    <div class="col-sm-6">
                        <div style="font-size: .72rem; text-transform: uppercase; letter-spacing: .5px; color: var(--ap-text-muted);">Client</div>
                        <div class="fw-semibold">{{ $order->customer_name }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div style="font-size: .72rem; text-transform: uppercase; letter-spacing: .5px; color: var(--ap-text-muted);">Email</div>
                        <div class="fw-semibold">{{ $order->customer_email }}</div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('home') }}" class="btn btn-ap-outline-primary btn-sm px-4">
                    <i class="bi bi-house me-1"></i>Accueil
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-ap-primary btn-sm px-4">
                    <i class="bi bi-grid me-1"></i>Continuer mes achats
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
