@extends('layouts.admin')

@section('title', 'Nouvelle catégorie')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.categories.index') }}" style="color: var(--ap-text-muted); text-decoration: none; font-size: .88rem;">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
    <h1 class="fw-bold mb-0" style="font-size: 1.25rem;">Nouvelle catégorie</h1>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-4" style="font-size: .88rem;">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="max-width: 560px;">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf

        <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); padding: 1.5rem; margin-bottom: 1.25rem;">
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size: .85rem;">Nom <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-0">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active" style="font-size: .87rem;">
                        Catégorie active (visible sur le site)
                    </label>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-ap-accent">
                <i class="bi bi-check-lg me-1"></i>Créer
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-ap-outline-primary">Annuler</a>
        </div>
    </form>
</div>

@endsection
