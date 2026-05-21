@extends('layouts.public')

@section('title', $product->name)

@section('content')
<div class="container py-4 ap-product-detail">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small" style="font-size: .82rem;">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="text-decoration-none" style="color: var(--ap-text-muted);">Accueil</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('products.index') }}" class="text-decoration-none" style="color: var(--ap-text-muted);">Catalogue</a>
            </li>
            @if($product->category)
                <li class="breadcrumb-item">
                    <a href="{{ route('products.index', ['category' => $product->category->id]) }}"
                       class="text-decoration-none" style="color: var(--ap-text-muted);">
                        {{ $product->category->name }}
                    </a>
                </li>
            @endif
            <li class="breadcrumb-item active" style="color: var(--ap-text);">
                {{ Str::limit($product->name, 45) }}
            </li>
        </ol>
    </nav>

    <div class="row g-4 g-lg-5">

        {{-- ===== Colonne images ===== --}}
        <div class="col-md-6">

            {{-- Image principale --}}
            <div class="img-main-wrap">
                @if($product->main_image_path)
                    <img
                        id="mainImg"
                        src="{{ asset($product->main_image_path) }}"
                        alt="{{ $product->name }}"
                        class="w-100 h-100"
                        style="object-fit: cover;"
                    >
                @else
                    <div class="img-main-placeholder">
                        <i class="bi bi-image"></i>
                    </div>
                @endif
            </div>

            {{-- Thumbnails --}}
            @if($product->images->count())
                <div class="thumbs-wrap">
                    @foreach($product->images as $img)
                        <img
                            src="{{ asset($img->path) }}"
                            alt="{{ $img->alt_text ?? $product->name }}"
                            class="thumb"
                            loading="lazy"
                            onclick="document.getElementById('mainImg').src='{{ asset($img->path) }}'; document.querySelectorAll('.thumb').forEach(t=>t.classList.remove('active')); this.classList.add('active');"
                        >
                    @endforeach
                </div>
            @endif

            {{-- Infos rapides sous l'image --}}
            <div class="mt-3 p-3 rounded" style="background: #EDE8DF; border: 1px solid var(--ap-border);">
                <div class="row g-2 text-center">
                    <div class="col-4">
                        <div style="font-size: .7rem; text-transform: uppercase; letter-spacing: .5px; color: var(--ap-text-muted); font-weight: 700;">État</div>
                        <div style="font-size: .88rem; font-weight: 600; color: var(--ap-text);">{{ $product->conditionLabel() }}</div>
                    </div>
                    <div class="col-4">
                        <div style="font-size: .7rem; text-transform: uppercase; letter-spacing: .5px; color: var(--ap-text-muted); font-weight: 700;">Stock</div>
                        <div style="font-size: .88rem; font-weight: 600; color: {{ $product->isInStock() ? 'var(--ap-success)' : 'var(--ap-danger)' }};">
                            {{ $product->isInStock() ? $product->stock_quantity . ' dispo' : 'Indisponible' }}
                        </div>
                    </div>
                    <div class="col-4">
                        <div style="font-size: .7rem; text-transform: uppercase; letter-spacing: .5px; color: var(--ap-text-muted); font-weight: 700;">Catégorie</div>
                        <div style="font-size: .88rem; font-weight: 600; color: var(--ap-text);">
                            {{ $product->category?->name ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Colonne détails ===== --}}
        <div class="col-md-6">

            {{-- Badges statut --}}
            <div class="d-flex gap-2 mb-3 flex-wrap">
                @if($product->category)
                    <span class="ap-badge ap-badge-cat">{{ $product->category->name }}</span>
                @endif
                @if($product->isInStock())
                    <span class="ap-badge ap-badge-stock">
                        <i class="bi bi-check-circle me-1"></i>En stock
                    </span>
                @else
                    <span class="ap-badge ap-badge-oos">
                        <i class="bi bi-x-circle me-1"></i>Indisponible
                    </span>
                @endif
                <span class="ap-badge ap-badge-info">{{ $product->conditionLabel() }}</span>
            </div>

            {{-- Titre --}}
            <h1 class="h3 fw-bold mb-3" style="color: var(--ap-text); line-height: 1.3;">
                {{ $product->name }}
            </h1>

            {{-- Références --}}
            <dl class="row info-table mb-3">
                <dt class="col-sm-5">Référence interne</dt>
                <dd class="col-sm-7">{{ $product->sku }}</dd>

                @if($product->oem_reference)
                    <dt class="col-sm-5">Réf. constructeur</dt>
                    <dd class="col-sm-7">{{ $product->oem_reference }}</dd>
                @endif

                @if($product->location)
                    <dt class="col-sm-5">Localisation</dt>
                    <dd class="col-sm-7" style="font-family: inherit;">{{ $product->location }}</dd>
                @endif
            </dl>

            {{-- Prix --}}
            <div class="mb-4 p-3 rounded" style="background: #EEE5D3; border-left: 4px solid var(--ap-primary);">
                <div class="product-price-big">
                    {{ number_format($product->price, 2, ',', ' ') }} <small>€ TTC</small>
                </div>
                <div style="font-size: .78rem; color: var(--ap-text-muted); margin-top: .2rem;">
                    Prix pièce d'occasion
                </div>
            </div>

            {{-- Description --}}
            @if($product->description)
                <div class="mb-4">
                    <h6 class="fw-bold mb-2" style="font-size: .82rem; text-transform: uppercase; letter-spacing: .5px; color: var(--ap-text-muted);">Description</h6>
                    <p class="text-muted mb-0" style="font-size: .92rem; line-height: 1.7;">
                        {{ $product->description }}
                    </p>
                </div>
            @endif

            {{-- Compatibilités --}}
            @if($product->compatibilities->count())
                <div class="mb-4">
                    <h6 class="fw-bold mb-2" style="font-size: .82rem; text-transform: uppercase; letter-spacing: .5px; color: var(--ap-text-muted);">
                        <i class="bi bi-car-front me-1"></i>Compatibilités véhicule
                    </h6>
                    <div style="border: 1px solid var(--ap-border); border-radius: var(--ap-radius-sm); overflow: hidden;">
                        @foreach($product->compatibilities as $compat)
                            <div class="compat-item px-3">
                                <i class="bi bi-check-circle-fill flex-shrink-0" style="color: var(--ap-success); font-size: .85rem;"></i>
                                <div>
                                    <strong>{{ $compat->vehicleMake?->name }}</strong>
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
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Actions --}}
            @if($product->isInStock())
                <div class="p-3 rounded" style="background: #EDE8DF; border: 1px solid var(--ap-border);">
                    <form action="{{ route('cart.add') }}" method="POST" class="js-cart-add">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="d-flex gap-2 align-items-center">
                            <div>
                                <label class="form-label mb-1" style="font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Quantité</label>
                                <input
                                    type="number"
                                    name="quantity"
                                    value="1"
                                    min="1"
                                    max="{{ $product->stock_quantity }}"
                                    class="form-control text-center fw-bold"
                                    style="width: 80px; border: 1.5px solid var(--ap-border); border-radius: var(--ap-radius-sm);"
                                >
                            </div>
                            <button type="submit"
                                    class="btn btn-ap-accent flex-grow-1"
                                    style="height: 48px; font-size: 1rem; margin-top: 24px;">
                                <i class="bi bi-cart-plus me-2"></i>Ajouter au panier
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="p-3 rounded mb-3" style="background: var(--ap-bg-card)7ed; border: 1.5px solid #fed7aa; border-radius: var(--ap-radius);">
                    <div class="d-flex gap-2 align-items-center">
                        <i class="bi bi-exclamation-triangle-fill" style="color: var(--ap-warning); font-size: 1.1rem; flex-shrink: 0;"></i>
                        <div>
                            <div class="fw-bold" style="font-size: .88rem; color: #92400e;">Pièce temporairement indisponible</div>
                            <div class="text-muted" style="font-size: .8rem;">Vous pouvez soumettre une demande de recherche.</div>
                        </div>
                    </div>
                </div>
                <a
                    href="{{ route('part-requests.create', [
                        'part'       => $product->name,
                        'reference'  => $product->sku,
                        'product_id' => $product->id,
                    ]) }}"
                    class="btn btn-ap-primary w-100"
                    style="height: 48px; font-size: 1rem; display: flex; align-items: center; justify-content: center;"
                >
                    <i class="bi bi-search me-2"></i>Je recherche cette pièce
                </a>
            @endif

        </div>
    </div>

    {{-- Retour catalogue --}}
    <div class="mt-5 pt-3" style="border-top: 1px solid var(--ap-border);">
        <a href="{{ route('products.index') }}"
           class="text-decoration-none d-inline-flex align-items-center gap-2"
           style="color: var(--ap-text-muted); font-size: .88rem;">
            <i class="bi bi-arrow-left"></i>Retour au catalogue
        </a>
    </div>

</div>
@endsection
