@extends('layouts.public')

@section('title', 'Commande confirmée')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            {{-- Confirmation icon --}}
            <div class="text-center mb-4">
                <div style="width: 72px; height: 72px; background: #E8F4EC; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem; color: var(--ap-success);">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h2 class="fw-bold mb-1" style="color: var(--ap-text);">Commande enregistrée !</h2>
                <p class="text-muted">
                    Votre commande <strong>#{{ $order->order_number }}</strong> a été créée avec succès.
                    Le paiement sera disponible prochainement.
                </p>
            </div>

            {{-- Détails commande --}}
            <div style="background: var(--ap-bg-card); border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden;">

                {{-- Header --}}
                <div class="px-4 py-3 d-flex justify-content-between align-items-center"
                     style="background: #EDE8DF; border-bottom: 1px solid var(--ap-border);">
                    <span class="fw-bold" style="font-size: .85rem; color: var(--ap-text);">
                        Commande #{{ $order->order_number }}
                    </span>
                    <span class="ap-badge" style="background: #fef3c7; color: #92400e; font-size: .72rem;">
                        {{ $order->statusLabel() }}
                    </span>
                </div>

                {{-- Lignes --}}
                <div class="p-4">
                    @foreach($order->items as $item)
                        <div class="d-flex justify-content-between mb-2" style="font-size: .9rem;">
                            <span class="text-muted">{{ $item->product_name }} × {{ $item->quantity }}</span>
                            <span class="fw-semibold">{{ number_format($item->line_total, 0, ',', ' ') }}</span>
                        </div>
                    @endforeach

                    <hr style="border-color: var(--ap-border);">

                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold" style="font-size: 1.05rem; color: var(--ap-primary);">
                            {{ number_format($order->total, 0, ',', ' ') }} {{ $order->currency }}
                        </span>
                    </div>
                </div>

                {{-- Infos client --}}
                <div class="px-4 pb-4">
                    <div style="background: #EDE8DF; border-radius: 8px; padding: 1rem; font-size: .85rem;">
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <div class="text-muted" style="font-size: .72rem; text-transform: uppercase; letter-spacing: .5px;">Nom</div>
                                <div class="fw-semibold">{{ $order->customer_name }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-muted" style="font-size: .72rem; text-transform: uppercase; letter-spacing: .5px;">Email</div>
                                <div class="fw-semibold">{{ $order->customer_email }}</div>
                            </div>
                            @if($order->customer_phone)
                                <div class="col-sm-6">
                                    <div class="text-muted" style="font-size: .72rem; text-transform: uppercase; letter-spacing: .5px;">Téléphone</div>
                                    <div class="fw-semibold">{{ $order->customer_phone }}</div>
                                </div>
                            @endif
                            <div class="col-sm-6">
                                <div class="text-muted" style="font-size: .72rem; text-transform: uppercase; letter-spacing: .5px;">Statut paiement</div>
                                <div class="fw-semibold" style="color: var(--ap-warning);">{{ $order->paymentStatusLabel() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Paiement placeholder --}}
                <div class="px-4 pb-4">
                    <div style="background: #EEE5D3; border-radius: 8px; padding: 1rem; text-align: center;">
                        <i class="bi bi-credit-card" style="font-size: 1.5rem; color: var(--ap-primary); margin-bottom: .5rem; display: block;"></i>
                        <div class="fw-bold mb-1" style="font-size: .9rem; color: var(--ap-primary);">Paiement en ligne</div>
                        <div class="text-muted" style="font-size: .8rem;">
                            Le module de paiement (PayPal / Mobile Money) sera disponible prochainement.
                            Notre équipe vous contactera pour finaliser votre commande.
                        </div>
                    </div>
                </div>

            </div>

            {{-- Actions --}}
            <div class="d-flex gap-3 justify-content-center mt-4 flex-wrap">
                <a href="{{ route('home') }}"
                   class="btn btn-ap-outline-primary btn-sm px-4">
                    <i class="bi bi-house me-1"></i>Retour à l'accueil
                </a>
                <a href="{{ route('products.index') }}"
                   class="btn btn-ap-primary btn-sm px-4">
                    <i class="bi bi-grid me-1"></i>Continuer mes achats
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
