@props(['product'])

<div class="card h-100 shadow-sm">
    <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
        @if($product->main_image_path)
            <img
                src="{{ asset($product->main_image_path) }}"
                alt="{{ $product->name }}"
                class="card-img-top object-fit-cover"
                style="height: 200px;"
                loading="lazy"
            >
        @else
            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
            </div>
        @endif
    </a>
    <div class="card-body d-flex flex-column">
        <div class="mb-2">
            @if($product->category)
                <span class="badge bg-secondary small">{{ $product->category->name }}</span>
            @endif
            <span class="badge ms-1 {{ $product->isInStock() ? 'bg-success' : 'bg-danger' }} small">
                {{ $product->isInStock() ? 'En stock' : 'Indisponible' }}
            </span>
        </div>
        <h6 class="card-title">
            <a href="{{ route('products.show', $product->slug) }}" class="text-dark text-decoration-none">
                {{ $product->name }}
            </a>
        </h6>
        <p class="text-muted small mb-1">
            <i class="bi bi-upc me-1"></i>{{ $product->sku }}
        </p>
        <p class="text-muted small mb-2">{{ $product->conditionLabel() }}</p>
        <div class="mt-auto">
            <p class="fw-bold mb-2">{{ number_format($product->price, 0, ',', ' ') }} {{ $product->currency }}</p>
            @if($product->isInStock())
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-dark btn-sm w-100">
                        <i class="bi bi-cart-plus me-1"></i>Ajouter au panier
                    </button>
                </form>
            @else
                <a
                    href="{{ route('part-requests.create', ['part' => $product->name, 'reference' => $product->sku]) }}"
                    class="btn btn-outline-secondary btn-sm w-100"
                >
                    <i class="bi bi-search me-1"></i>Je recherche cette pièce
                </a>
            @endif
        </div>
    </div>
</div>
