@extends('layouts.public')

@section('title', 'Catalogue — Pièces auto d\'occasion')

@section('content')
<div class="container py-4">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 fw-bold mb-0" style="color: var(--ap-text);">Catalogue</h1>
            <p class="text-muted small mb-0">
                {{ $products->total() }} pièce{{ $products->total() > 1 ? 's' : '' }} disponible{{ $products->total() > 1 ? 's' : '' }}
                @if(request('q')) — recherche : <strong>« {{ request('q') }} »</strong> @endif
            </p>
        </div>
        <a href="{{ route('part-requests.create') }}"
           class="btn btn-ap-outline-primary btn-sm">
            <i class="bi bi-question-circle me-1"></i>Pièce introuvable ?
        </a>
    </div>

    {{-- Filtres --}}
    <div class="ap-filter-panel">
        @if($singleMake)
            <div class="d-inline-flex align-items-center gap-2 mb-3 px-3 py-1 rounded-pill"
                 style="background: #EEE5D3; font-size: .82rem; font-weight: 600; color: var(--ap-primary);">
                <i class="bi bi-star-fill" style="font-size: .7rem;"></i>
                Spécialiste {{ $singleMake->name }}
            </div>
        @endif
        <form action="{{ route('products.index') }}" method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label">Recherche</label>
                    <input
                        type="text"
                        name="q"
                        class="form-control"
                        value="{{ request('q') }}"
                        placeholder="Nom, SKU, référence constructeur…"
                        autocomplete="off"
                    >
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">Catégorie</label>
                    <select name="category" class="form-select">
                        <option value="">Toutes</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if(!$singleMake)
                    {{-- Filtre marque visible uniquement si mode multi-marques --}}
                    <div class="col-6 col-md-2">
                        <label class="form-label">Marque</label>
                        <select id="make-select" name="make" class="form-select">
                            <option value="">Toutes</option>
                            @foreach($makes as $mk)
                                <option value="{{ $mk->id }}" {{ request('make') == $mk->id ? 'selected' : '' }}>
                                    {{ $mk->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-6 col-md-3">
                    <label class="form-label">Modèle {{ $singleMake ? $singleMake->name : '' }}</label>
                    <select name="model" class="form-select">
                        <option value="">Tous</option>
                        @foreach($selectedModels as $mdl)
                            <option value="{{ $mdl->id }}" {{ request('model') == $mdl->id ? 'selected' : '' }}>
                                {{ $mdl->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-ap-primary flex-grow-1">
                        <i class="bi bi-search me-1"></i>Filtrer
                    </button>
                    @if(request()->hasAny(['q','category','make','model']))
                        <a href="{{ route('products.index') }}"
                           class="btn btn-sm"
                           style="background: #EDE8DF; color: var(--ap-text-muted); border: 1px solid var(--ap-border);"
                           title="Réinitialiser les filtres">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Filtres actifs --}}
            @if(request()->hasAny(['q','category','make','model']))
                <div class="mt-2 d-flex gap-2 flex-wrap align-items-center">
                    <small class="text-muted">Filtres actifs :</small>
                    @if(request('q'))
                        <span class="badge" style="background: #EEE5D3; color: var(--ap-primary); font-weight: 600; font-size: .75rem;">
                            <i class="bi bi-search me-1"></i>{{ request('q') }}
                        </span>
                    @endif
                    @if(request('category'))
                        <span class="badge" style="background: #EEE5D3; color: var(--ap-primary); font-weight: 600; font-size: .75rem;">
                            <i class="bi bi-tag me-1"></i>Catégorie
                        </span>
                    @endif
                    @if(!$singleMake && request('make'))
                        <span class="badge" style="background: #EEE5D3; color: var(--ap-primary); font-weight: 600; font-size: .75rem;">
                            <i class="bi bi-car-front me-1"></i>Marque
                        </span>
                    @endif
                    @if(request('model'))
                        <span class="badge" style="background: #EEE5D3; color: var(--ap-primary); font-weight: 600; font-size: .75rem;">
                            <i class="bi bi-car-front me-1"></i>Modèle
                        </span>
                    @endif
                </div>
            @endif
        </form>
    </div>

    {{-- Grille produits --}}
    @if($products->count())
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($products as $product)
                <div class="col">
                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="mt-5 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        @endif

    @else
        {{-- État vide --}}
        <div class="text-center py-5 mt-2">
            <div style="width: 80px; height: 80px; background: #EEE5D3; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2rem; color: var(--ap-primary);">
                <i class="bi bi-search"></i>
            </div>
            <h5 class="fw-bold mb-1">Aucune pièce trouvée</h5>
            <p class="text-muted mb-3">
                @if(request('q'))
                    Aucun résultat pour « {{ request('q') }} ». Essayez d'autres termes.
                @else
                    Aucun produit ne correspond à vos critères de recherche.
                @endif
            </p>
            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <a href="{{ route('products.index') }}"
                   class="btn btn-ap-outline-primary btn-sm">
                    <i class="bi bi-x me-1"></i>Effacer les filtres
                </a>
                <a href="{{ route('part-requests.create') }}"
                   class="btn btn-ap-accent btn-sm">
                    <i class="bi bi-search me-1"></i>Soumettre une demande
                </a>
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
const makeSelect = document.getElementById('make-select');
if (makeSelect) {
    makeSelect.addEventListener('change', function () {
        const makeId = this.value;
        const url = new URL(window.location.href);
        url.searchParams.set('make', makeId);
        url.searchParams.delete('model');
        if (!makeId) url.searchParams.delete('make');
        window.location.href = url.toString();
    });
}
</script>
@endpush
