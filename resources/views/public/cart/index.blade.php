@extends('layouts.public')

@section('title', 'Mon panier')

@section('content')
<div class="container py-4">

    <h1 class="h3 fw-bold mb-4" style="color: var(--ap-text);">
        <i class="bi bi-cart3 me-2" style="color: var(--ap-accent);"></i>Mon panier
    </h1>

    @if($items->isEmpty())
        {{-- Panier vide --}}
        <div class="text-center py-5">
            <div style="width: 80px; height: 80px; background: #EEE5D3; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2.2rem; color: var(--ap-primary);">
                <i class="bi bi-cart-x"></i>
            </div>
            <h5 class="fw-bold mb-2">Votre panier est vide</h5>
            <p class="text-muted mb-4">Parcourez notre catalogue pour trouver les pièces dont vous avez besoin.</p>
            <a href="{{ route('products.index') }}" class="btn btn-ap-primary px-4">
                <i class="bi bi-grid me-2"></i>Voir le catalogue
            </a>
        </div>

    @else
        <div class="row g-4">

            {{-- Tableau panier --}}
            <div class="col-lg-8">
                <div style="background: var(--ap-bg-card); border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden;">

                    {{-- En-tête tableau --}}
                    <div class="px-4 py-3 d-flex justify-content-between align-items-center"
                         style="border-bottom: 1px solid var(--ap-border); background: #EDE8DF;">
                        <span class="fw-bold" style="font-size: .85rem; text-transform: uppercase; letter-spacing: .5px; color: var(--ap-text-muted);">
                            {{ $items->count() }} article{{ $items->count() > 1 ? 's' : '' }}
                        </span>
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-sm"
                                    style="background: none; border: none; color: var(--ap-danger); font-size: .82rem; padding: 0;"
                                    onclick="return confirm('Vider le panier ?')">
                                <i class="bi bi-trash3 me-1"></i>Vider le panier
                            </button>
                        </form>
                    </div>

                    {{-- Lignes --}}
                    @foreach($items as $line)
                        <div class="p-4 d-flex gap-3 align-items-start"
                             style="{{ ! $loop->last ? 'border-bottom: 1px solid var(--ap-border);' : '' }}">

                            {{-- Image --}}
                            <a href="{{ route('products.show', $line->product->slug) }}"
                               style="flex-shrink: 0; width: 80px; height: 80px; background: #EDE8DF; border-radius: 8px; overflow: hidden; display: block;">
                                @if($line->product->main_image_path)
                                    <img src="{{ asset($line->product->main_image_path) }}"
                                         alt="{{ $line->product->name }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 1.5rem;">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </a>

                            {{-- Infos --}}
                            <div class="flex-grow-1 min-width-0">
                                <a href="{{ route('products.show', $line->product->slug) }}"
                                   class="fw-bold text-decoration-none d-block mb-1"
                                   style="color: var(--ap-text); font-size: .95rem; line-height: 1.3;">
                                    {{ $line->product->name }}
                                </a>
                                <div style="font-size: .75rem; color: var(--ap-text-muted); font-family: monospace;">
                                    {{ $line->product->sku }}
                                </div>
                                @if($line->product->category)
                                    <span class="ap-badge ap-badge-cat mt-1">{{ $line->product->category->name }}</span>
                                @endif

                                {{-- Prix unitaire mobile --}}
                                <div class="d-sm-none mt-2 fw-bold" style="color: var(--ap-primary);">
                                    {{ number_format($line->unit_price, 2, ',', ' ') }} €
                                </div>
                            </div>

                            {{-- Prix unitaire desktop --}}
                            <div class="d-none d-sm-block text-center" style="min-width: 100px;">
                                <div class="fw-bold" style="color: var(--ap-primary); font-size: .95rem;">
                                    {{ number_format($line->unit_price, 2, ',', ' ') }} €
                                </div>
                            </div>

                            {{-- Quantité --}}
                            <form action="{{ route('cart.update') }}" method="POST"
                                  class="d-flex align-items-center gap-1" style="flex-shrink: 0;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="product_id" value="{{ $line->product->id }}">
                                <input type="number"
                                       name="quantity"
                                       value="{{ $line->quantity }}"
                                       min="1"
                                       max="{{ $line->product->stock_quantity }}"
                                       class="form-control form-control-sm text-center"
                                       style="width: 60px; border: 1.5px solid var(--ap-border); border-radius: 6px;"
                                       onchange="this.form.submit()">
                            </form>

                            {{-- Total ligne --}}
                            <div class="d-none d-sm-block text-end fw-bold"
                                 style="min-width: 110px; color: var(--ap-primary); font-size: 1rem;">
                                {{ number_format($line->line_total, 2, ',', ' ') }} €
                            </div>

                            {{-- Supprimer --}}
                            <form action="{{ route('cart.remove', $line->product->id) }}" method="POST" style="flex-shrink: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm"
                                        style="background: none; border: none; color: #94a3b8; padding: .2rem .4rem;"
                                        title="Retirer">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>

                        </div>
                    @endforeach
                </div>

                {{-- Continuer --}}
                <div class="mt-3">
                    <a href="{{ route('products.index') }}"
                       class="text-decoration-none d-inline-flex align-items-center gap-2"
                       style="color: var(--ap-text-muted); font-size: .88rem;">
                        <i class="bi bi-arrow-left"></i>Continuer mes achats
                    </a>
                </div>
            </div>

            {{-- Récap commande --}}
            <div class="col-lg-4">
                <div style="background: var(--ap-bg-card); border: 1px solid var(--ap-border); border-radius: var(--ap-radius); padding: 1.5rem; position: sticky; top: 80px;">
                    <h6 class="fw-bold mb-3" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">
                        Récapitulatif
                    </h6>

                    @foreach($items as $line)
                        <div class="d-flex justify-content-between mb-2" style="font-size: .88rem;">
                            <span class="text-muted" style="max-width: 65%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $line->product->name }} × {{ $line->quantity }}
                            </span>
                            <span class="fw-semibold">{{ number_format($line->line_total, 2, ',', ' ') }} €</span>
                        </div>
                    @endforeach

                    <hr style="border-color: var(--ap-border); margin: 1rem 0;">

                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size: .88rem; color: var(--ap-text-muted);">Sous-total</span>
                        <span class="fw-bold">{{ number_format($total, 2, ',', ' ') }} €</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span style="font-size: .82rem; color: var(--ap-text-muted);">Livraison</span>
                        <span style="font-size: .82rem; color: var(--ap-text-muted);">À définir</span>
                    </div>

                    <div class="d-flex justify-content-between mb-4 pt-2" style="border-top: 2px solid var(--ap-border);">
                        <span class="fw-bold" style="font-size: 1rem;">Total</span>
                        <span class="fw-bold" style="font-size: 1.1rem; color: var(--ap-primary);">
                            {{ number_format($total, 2, ',', ' ') }} €
                        </span>
                    </div>

                    <a href="{{ route('checkout.show') }}"
                       class="btn btn-ap-accent w-100"
                       style="height: 48px; font-size: 1rem; display: flex; align-items: center; justify-content: center; gap: .5rem;">
                        <i class="bi bi-lock-fill"></i>Passer commande
                    </a>

                    @guest
                        <p class="text-center mt-2 mb-0" style="font-size: .78rem; color: var(--ap-text-muted);">
                            <a href="{{ route('login') }}" style="color: var(--ap-accent);">Connexion requise</a> pour commander
                        </p>
                    @endguest
                </div>
            </div>

        </div>
    @endif

</div>
@endsection
