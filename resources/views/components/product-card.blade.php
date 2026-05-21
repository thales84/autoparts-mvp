@props(['product'])

<div class="ap-product-card">
    {{-- Image --}}
    <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none d-block">
        <div class="card-img-wrap">
            @if($product->main_image_path)
                <img
                    src="{{ asset($product->main_image_path) }}"
                    alt="{{ $product->name }}"
                    loading="lazy"
                >
            @else
                <div class="card-img-placeholder">
                    <i class="bi bi-image"></i>
                </div>
            @endif

            {{-- Badges --}}
            <div class="card-badges">
                <span class="ap-badge {{ $product->isInStock() ? 'ap-badge-stock' : 'ap-badge-oos' }}">
                    {{ $product->isInStock() ? 'En stock' : 'Indisponible' }}
                </span>
                @if($product->category)
                    <span class="ap-badge ap-badge-cat">{{ $product->category->name }}</span>
                @endif
            </div>
        </div>
    </a>

    {{-- Body --}}
    <div class="card-body">
        <a href="{{ route('products.show', $product->slug) }}" class="product-name">
            {{ $product->name }}
        </a>
        <div class="product-sku">
            <i class="bi bi-upc me-1"></i>{{ $product->sku }}
        </div>
        <div class="product-condition">{{ $product->conditionLabel() }}</div>

        <div class="product-price">
            {{ number_format($product->price, 2, ',', ' ') }} <small>€</small>
        </div>
    </div>

    {{-- Actions --}}
    <div class="card-actions">
        @if($product->isInStock())
            <form action="{{ route('cart.add') }}" method="POST" class="w-100 js-cart-add">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn btn-ap-primary btn-sm w-100">
                    <i class="bi bi-cart-plus me-1"></i>Ajouter au panier
                </button>
            </form>
        @else
            <a
                href="{{ route('part-requests.create', ['part' => $product->name, 'reference' => $product->sku]) }}"
                class="btn btn-ap-outline-primary btn-sm w-100"
            >
                <i class="bi bi-search me-1"></i>Je recherche cette pièce
            </a>
        @endif
    </div>
</div>
