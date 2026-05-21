@extends('layouts.admin')

@section('title', 'Modifier le produit')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.products.index') }}" style="color: var(--ap-text-muted); text-decoration: none; font-size: .88rem;">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
    <h1 class="fw-bold mb-0" style="font-size: 1.25rem;">Modifier le produit</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" style="font-size: .88rem;" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger mb-4" style="font-size: .88rem;">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">

        {{-- Colonne principale --}}
        <div class="col-lg-8">

            {{-- Informations générales --}}
            <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); padding: 1.5rem; margin-bottom: 1.25rem;">
                <div class="fw-bold mb-4" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">Informations générales</div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size: .85rem;">Nom du produit <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $product->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size: .85rem;">Description</label>
                    <textarea name="description" rows="5"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-0">
                    <label class="form-label fw-semibold" style="font-size: .85rem;">Compatibilité véhicules</label>
                    <textarea name="compatibility_notes" rows="3"
                              class="form-control @error('compatibility_notes') is-invalid @enderror"
                              placeholder="Ex: Compatible Peugeot 206 (2000-2005), Citroën C3 phase 1...">{{ old('compatibility_notes', $product->compatibility_notes) }}</textarea>
                    @error('compatibility_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Prix & Stock --}}
            <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); padding: 1.5rem;">
                <div class="fw-bold mb-4" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">Prix & Stock</div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size: .85rem;">État <span class="text-danger">*</span></label>
                    <select name="condition" class="form-select @error('condition') is-invalid @enderror">
                        <option value="used_good"   {{ old('condition', $product->condition) === 'used_good'   ? 'selected' : '' }}>Occasion — bon état</option>
                        <option value="used_fair"   {{ old('condition', $product->condition) === 'used_fair'   ? 'selected' : '' }}>Occasion — état correct</option>
                        <option value="refurbished" {{ old('condition', $product->condition) === 'refurbished' ? 'selected' : '' }}>Reconditionné</option>
                        <option value="for_parts"   {{ old('condition', $product->condition) === 'for_parts'   ? 'selected' : '' }}>Pour pièces</option>
                    </select>
                    @error('condition')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3">
                    <div class="col-sm-4">
                        <label class="form-label fw-semibold" style="font-size: .85rem;">Prix <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="price" step="0.01" min="0"
                                   class="form-control @error('price') is-invalid @enderror"
                                   value="{{ old('price', $product->price) }}" required>
                            <span class="input-group-text" style="font-size: .85rem;">€</span>
                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label fw-semibold" style="font-size: .85rem;">Stock <span class="text-danger">*</span></label>
                        <input type="number" name="stock_quantity" min="0"
                               class="form-control @error('stock_quantity') is-invalid @enderror"
                               value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                        @error('stock_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label fw-semibold" style="font-size: .85rem;">Référence (SKU)</label>
                        <input type="text" name="sku"
                               class="form-control font-monospace @error('sku') is-invalid @enderror"
                               value="{{ old('sku', $product->sku) }}">
                        @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

        </div>

        {{-- Colonne latérale --}}
        <div class="col-lg-4">

            {{-- Statut & Catégorie --}}
            <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); padding: 1.5rem; margin-bottom: 1.25rem;">
                <div class="fw-bold mb-4" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">Organisation</div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size: .85rem;">Statut</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactif</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-0">
                    <label class="form-label fw-semibold" style="font-size: .85rem;">Catégorie</label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                        <option value="">— Sans catégorie —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Image --}}
            <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); padding: 1.5rem; margin-bottom: 1.25rem;">
                <div class="fw-bold mb-4" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">Image principale</div>

                <div id="image-preview"
                     style="width: 100%; aspect-ratio: 1; background: #f8fafc; border: 2px dashed var(--ap-border); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: .75rem; overflow: hidden; cursor: pointer;"
                     onclick="document.getElementById('image-input').click()">
                    @if($product->main_image_path)
                        <img src="{{ asset($product->main_image_path) }}"
                             alt="{{ $product->name }}"
                             style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div class="text-center" style="color: var(--ap-text-muted); font-size: .8rem;">
                            <i class="bi bi-image d-block mb-1" style="font-size: 1.5rem;"></i>
                            Cliquer pour choisir
                        </div>
                    @endif
                </div>

                <input type="file" name="image" id="image-input" accept="image/*"
                       class="d-none @error('image') is-invalid @enderror"
                       onchange="previewImage(this)">
                @error('image')<div class="text-danger mt-1" style="font-size: .8rem;">{{ $message }}</div>@enderror

                @if($product->main_image_path)
                    <div style="font-size: .75rem; color: var(--ap-text-muted);">Choisir une nouvelle image pour remplacer l'actuelle.</div>
                @endif
            </div>

            {{-- Boutons --}}
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-ap-accent">
                    <i class="bi bi-check-lg me-1"></i>Enregistrer
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-ap-outline-primary">
                    Annuler
                </a>
            </div>

        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
