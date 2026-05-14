@extends('layouts.public')

@section('title', $product->name)

@section('content')
<div class="container py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Catalogue</a></li>
            @if($product->category)
                <li class="breadcrumb-item">
                    <a href="{{ route('products.index', ['category' => $product->category->id]) }}">
                        {{ $product->category->name }}
                    </a>
                </li>
            @endif
            <li class="breadcrumb-item active">{{ Str::limit($product->name, 50) }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Images --}}
        <div class="col-md-6">
            @if($product->main_image_path)
                <img
                    src="{{ asset($product->main_image_path) }}"
                    alt="{{ $product->name }}"
                    class="img-fluid rounded shadow-sm w-100 object-fit-cover"
                    style="max-height: 420px;"
                >
            @else
                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 420px;">
                    <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                </div>
            @endif

            @if($product->images->count())
                <div class="d-flex gap-2 mt-2 flex-wrap">
                    @foreach($product->images as $img)
                        <img
                            src="{{ asset($img->path) }}"
                            alt="{{ $img->alt_text ?? $product->name }}"
                            class="rounded border"
                            style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                            onclick="document.querySelector('.main-img').src='{{ asset($img->path) }}'"
                            loading="lazy"
                        >
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Détails --}}
        <div class="col-md-6">
            <div class="d-flex gap-2 mb-2 flex-wrap">
                @if($product->category)
                    <span class="badge bg-secondary">{{ $product->category->name }}</span>
                @endif
                <span class="badge {{ $product->isInStock() ? 'bg-success' : 'bg-danger' }}">
                    {{ $product->isInStock() ? 'En stock (' . $product->stock_quantity . ')' : 'Indisponible' }}
                </span>
                <span class="badge bg-info text-dark">{{ $product->conditionLabel() }}</span>
            </div>

            <h1 class="h3 mb-2">{{ $product->name }}</h1>

            <dl class="row small text-muted mb-3">
                <dt class="col-sm-4">Référence interne</dt>
                <dd class="col-sm-8 font-monospace">{{ $product->sku }}</dd>
                @if($product->oem_reference)
                    <dt class="col-sm-4">Réf. constructeur</dt>
                    <dd class="col-sm-8 font-monospace">{{ $product->oem_reference }}</dd>
                @endif
                @if($product->location)
                    <dt class="col-sm-4">Localisation</dt>
                    <dd class="col-sm-8">{{ $product->location }}</dd>
                @endif
            </dl>

            <p class="display-6 fw-bold mb-3">
                {{ number_format($product->price, 0, ',', ' ') }}
                <small class="fs-5 text-muted">{{ $product->currency }}</small>
            </p>

            {{-- Description --}}
            <div class="mb-4">
                <h6 class="fw-semibold">Description</h6>
                <p class="text-muted">{{ $product->description }}</p>
            </div>

            {{-- Compatibilités --}}
            @if($product->compatibilities->count())
                <div class="mb-4">
                    <h6 class="fw-semibold">Compatibilités véhicule</h6>
                    <ul class="list-unstyled">
                        @foreach($product->compatibilities as $compat)
                            <li class="mb-1 small">
                                <i class="bi bi-check-circle-fill text-success me-1"></i>
                                {{ $compat->vehicleMake?->name }}
                                @if($compat->vehicleModel)
                                    {{ $compat->vehicleModel->name }}
                                @endif
                                @if($compat->year_from || $compat->year_to)
                                    <span class="text-muted">
                                        ({{ $compat->year_from ?? '?' }} – {{ $compat->year_to ?? 'présent' }})
                                    </span>
                                @endif
                                @if($compat->notes)
                                    <span class="text-muted">— {{ $compat->notes }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Action --}}
            @if($product->isInStock())
                <form action="{{ route('cart.add') }}" method="POST" class="d-flex gap-2 align-items-center">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div style="width: 100px;">
                        <input
                            type="number"
                            name="quantity"
                            value="1"
                            min="1"
                            max="{{ $product->stock_quantity }}"
                            class="form-control"
                        >
                    </div>
                    <button type="submit" class="btn btn-dark btn-lg flex-grow-1">
                        <i class="bi bi-cart-plus me-1"></i>Ajouter au panier
                    </button>
                </form>
            @else
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Cette pièce n'est pas disponible en stock.
                </div>
                <a
                    href="{{ route('part-requests.create', [
                        'part'       => $product->name,
                        'reference'  => $product->sku,
                        'product_id' => $product->id,
                    ]) }}"
                    class="btn btn-outline-dark btn-lg w-100"
                >
                    <i class="bi bi-search me-1"></i>Je recherche cette pièce
                </a>
            @endif
        </div>
    </div>

    {{-- Retour catalogue --}}
    <div class="mt-5">
        <a href="{{ route('products.index') }}" class="text-muted text-decoration-none small">
            <i class="bi bi-arrow-left me-1"></i>Retour au catalogue
        </a>
    </div>
</div>
@endsection
