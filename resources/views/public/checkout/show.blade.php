@extends('layouts.public')

@section('title', 'Passer commande')

@section('content')
<div class="container py-4">

    <h1 class="h3 fw-bold mb-4" style="color: var(--ap-text);">
        <i class="bi bi-lock-fill me-2" style="color: var(--ap-accent);"></i>Passer commande
    </h1>

    <form action="{{ route('checkout.store') }}" method="POST" novalidate>
        @csrf

        <div class="row g-4">

            {{-- Formulaire coordonnées --}}
            <div class="col-lg-7">

                {{-- Infos de contact --}}
                <div class="p-4 mb-3 rounded" style="background: var(--ap-bg-card); border: 1px solid var(--ap-border);">
                    <h6 class="fw-bold mb-3" style="font-size: .78rem; text-transform: uppercase; letter-spacing: .7px; color: var(--ap-text-muted);">
                        <i class="bi bi-person-fill me-1" style="color: var(--ap-accent);"></i>Vos informations
                    </h6>

                    <div class="mb-3">
                        <label for="customer_name" class="form-label" style="font-size: .82rem; font-weight: 700; color: var(--ap-text);">
                            Nom complet <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="customer_name"
                               name="customer_name"
                               class="form-control @error('customer_name') is-invalid @enderror"
                               value="{{ old('customer_name', $user->name) }}"
                               required>
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="customer_email" class="form-label" style="font-size: .82rem; font-weight: 700; color: var(--ap-text);">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                   id="customer_email"
                                   name="customer_email"
                                   class="form-control @error('customer_email') is-invalid @enderror"
                                   value="{{ old('customer_email', $user->email) }}"
                                   required>
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="customer_phone" class="form-label" style="font-size: .82rem; font-weight: 700; color: var(--ap-text);">Téléphone</label>
                            <input type="tel"
                                   id="customer_phone"
                                   name="customer_phone"
                                   class="form-control"
                                   value="{{ old('customer_phone', $user->phone ?? '') }}"
                                   placeholder="+237 6XX XXX XXX">
                        </div>
                    </div>
                </div>

                {{-- Adresse livraison --}}
                <div class="p-4 mb-3 rounded" style="background: var(--ap-bg-card); border: 1px solid var(--ap-border);">
                    <h6 class="fw-bold mb-3" style="font-size: .78rem; text-transform: uppercase; letter-spacing: .7px; color: var(--ap-text-muted);">
                        <i class="bi bi-geo-alt-fill me-1" style="color: var(--ap-accent);"></i>Adresse de livraison
                    </h6>
                    <textarea name="delivery_address"
                              class="form-control"
                              rows="3"
                              placeholder="Quartier, rue, ville, indication de localisation…"
                              style="resize: vertical;">{{ old('delivery_address') }}</textarea>
                </div>

                {{-- Notes --}}
                <div class="p-4 rounded" style="background: var(--ap-bg-card); border: 1px solid var(--ap-border);">
                    <h6 class="fw-bold mb-3" style="font-size: .78rem; text-transform: uppercase; letter-spacing: .7px; color: var(--ap-text-muted);">
                        <i class="bi bi-chat-text me-1" style="color: var(--ap-accent);"></i>Notes (optionnel)
                    </h6>
                    <textarea name="notes"
                              class="form-control"
                              rows="2"
                              placeholder="Instructions particulières pour votre commande…"
                              style="resize: vertical;">{{ old('notes') }}</textarea>
                </div>

            </div>

            {{-- Récapitulatif --}}
            <div class="col-lg-5">
                <div style="background: var(--ap-bg-card); border: 1px solid var(--ap-border); border-radius: var(--ap-radius); padding: 1.5rem; position: sticky; top: 80px;">

                    <h6 class="fw-bold mb-3" style="font-size: .78rem; text-transform: uppercase; letter-spacing: .7px; color: var(--ap-text-muted);">
                        Votre commande
                    </h6>

                    @foreach($items as $line)
                        <div class="d-flex gap-2 mb-3 align-items-center">
                            <div style="width: 44px; height: 44px; background: #EDE8DF; border-radius: 6px; overflow: hidden; flex-shrink: 0;">
                                @if($line->product->main_image_path)
                                    <img src="{{ asset($line->product->main_image_path) }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: .9rem;">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="fw-semibold text-truncate" style="font-size: .85rem;">{{ $line->product->name }}</div>
                                <div style="font-size: .75rem; color: var(--ap-text-muted);">Qté : {{ $line->quantity }}</div>
                            </div>
                            <div class="fw-bold text-end" style="font-size: .9rem; color: var(--ap-primary); flex-shrink: 0;">
                                {{ number_format($line->line_total, 2, ',', ' ') }} €
                            </div>
                        </div>
                    @endforeach

                    <hr style="border-color: var(--ap-border);">

                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold" style="font-size: 1.1rem; color: var(--ap-primary);">
                            {{ number_format($total, 2, ',', ' ') }} €
                        </span>
                    </div>

                    {{-- Erreurs panier --}}
                    @if($errors->has('cart'))
                        <div class="ap-flash ap-flash-error mb-3">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <span>{{ $errors->first('cart') }}</span>
                        </div>
                    @endif

                    <button type="submit"
                            class="btn btn-ap-accent w-100"
                            style="height: 50px; font-size: 1rem; font-weight: 700;">
                        <i class="bi bi-check-circle me-2"></i>Confirmer la commande
                    </button>

                    <p class="text-center mt-2 mb-0" style="font-size: .75rem; color: var(--ap-text-muted);">
                        <i class="bi bi-lock me-1"></i>Commande sécurisée — paiement sur la page suivante
                    </p>

                </div>
            </div>

        </div>
    </form>

</div>
@endsection
