@extends('layouts.public')

@section('title', 'Catalogue — Pièces auto d\'occasion')

@section('content')
<div class="container py-4">

    {{-- Titre + lien demande --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h2 class="mb-0">Catalogue</h2>
        <a href="{{ route('part-requests.create') }}" class="btn btn-outline-dark btn-sm">
            <i class="bi bi-question-circle me-1"></i>Vous ne trouvez pas votre pièce ?
        </a>
    </div>

    {{-- Formulaire de recherche/filtres --}}
    <form action="{{ route('products.index') }}" method="GET" class="card card-body mb-4 shadow-sm">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="q" class="form-label small fw-semibold">Recherche</label>
                <input
                    type="text"
                    id="q"
                    name="q"
                    class="form-control form-control-sm"
                    value="{{ request('q') }}"
                    placeholder="Nom, SKU, référence…"
                >
            </div>
            <div class="col-md-2">
                <label for="category" class="form-label small fw-semibold">Catégorie</label>
                <select id="category" name="category" class="form-select form-select-sm">
                    <option value="">Toutes</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="make" class="form-label small fw-semibold">Marque véhicule</label>
                <select id="make" name="make" class="form-select form-select-sm">
                    <option value="">Toutes</option>
                    @foreach($makes as $make)
                        <option value="{{ $make->id }}" {{ request('make') == $make->id ? 'selected' : '' }}>
                            {{ $make->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="model" class="form-label small fw-semibold">Modèle</label>
                <select id="model" name="model" class="form-select form-select-sm">
                    <option value="">Tous</option>
                    @foreach($selectedModels as $model)
                        <option value="{{ $model->id }}" {{ request('model') == $model->id ? 'selected' : '' }}>
                            {{ $model->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark btn-sm flex-grow-1">
                    <i class="bi bi-search me-1"></i>Filtrer
                </button>
                @if(request()->hasAny(['q','category','make','model']))
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>

    {{-- Résultats --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted small mb-0">
            {{ $products->total() }} résultat{{ $products->total() > 1 ? 's' : '' }}
            @if(request('q'))
                pour « {{ request('q') }} »
            @endif
        </p>
    </div>

    @if($products->count())
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($products as $product)
                <div class="col">
                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-search display-4 text-muted mb-3 d-block"></i>
            <h5 class="text-muted">Aucun produit trouvé</h5>
            <p class="text-muted">Essayez d'autres critères ou</p>
            <a href="{{ route('part-requests.create') }}" class="btn btn-outline-dark">
                Soumettre une demande de recherche
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Rechargement des modèles quand la marque change
document.getElementById('make').addEventListener('change', function () {
    const makeId = this.value;
    const url = new URL(window.location.href);
    url.searchParams.set('make', makeId);
    url.searchParams.delete('model');
    if (!makeId) {
        url.searchParams.delete('make');
    }
    window.location.href = url.toString();
});
</script>
@endpush
