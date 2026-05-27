@extends('layouts.public')

@section('title', 'Demande de pièce')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">

            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb small" style="font-size: .82rem;">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none" style="color: var(--ap-text-muted);">Accueil</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('products.index') }}" class="text-decoration-none" style="color: var(--ap-text-muted);">Catalogue</a>
                    </li>
                    <li class="breadcrumb-item active" style="color: var(--ap-text);">Demande de pièce</li>
                </ol>
            </nav>

            {{-- En-tête --}}
            <div class="d-flex gap-3 align-items-start mb-4">
                <div style="width: 52px; height: 52px; background: #EEE5D3; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: var(--ap-primary); flex-shrink: 0;">
                    <i class="bi bi-search-heart"></i>
                </div>
                <div>
                    <h1 class="h3 fw-bold mb-0" style="color: var(--ap-text);">Je recherche une pièce</h1>
                    <p class="text-muted mb-0" style="font-size: .9rem;">
                        Remplissez ce formulaire — notre équipe vous recontacte sous 24h.
                    </p>
                </div>
            </div>

            <form action="{{ route('part-requests.store') }}" method="POST" novalidate>
                @csrf

                {{-- === Bloc 1 : Pièce === --}}
                <div class="mb-3 p-4 rounded" style="background: var(--ap-bg-card); border: 1px solid var(--ap-border);">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2"
                        style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">
                        <i class="bi bi-gear-wide-connected" style="color: var(--ap-accent);"></i>Pièce recherchée
                    </h6>

                    <div class="mb-3">
                        <label for="requested_part_name" class="form-label">
                            Nom de la pièce <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="requested_part_name"
                            name="requested_part_name"
                            class="form-control @error('requested_part_name') is-invalid @enderror"
                            value="{{ old('requested_part_name', $prefill['requested_part_name']) }}"
                            placeholder="Alternateur, plaquettes de frein avant, radiateur…"
                            required
                        >
                        @error('requested_part_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="reference" class="form-label">Référence connue</label>
                        <input
                            type="text"
                            id="reference"
                            name="reference"
                            class="form-control @error('reference') is-invalid @enderror"
                            value="{{ old('reference', $prefill['reference']) }}"
                            placeholder="Référence OEM ou code pièce"
                        >
                        @error('reference')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="form-label">Détails supplémentaires</label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            rows="3"
                            placeholder="État souhaité, urgence, remarques particulières…"
                            style="resize: vertical;"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- === Bloc 2 : Véhicule === --}}
                <div class="mb-3 p-4 rounded" style="background: var(--ap-bg-card); border: 1px solid var(--ap-border);">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2"
                        style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">
                        <i class="bi bi-car-front" style="color: var(--ap-accent);"></i>Véhicule concerné
                    </h6>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="vehicle_make" class="form-label">Marque</label>
                            <input
                                type="text"
                                id="vehicle_make"
                                name="vehicle_make"
                                class="form-control @error('vehicle_make') is-invalid @enderror {{ $singleMake ? 'bg-light' : '' }}"
                                value="{{ old('vehicle_make', $singleMake?->name ?? '') }}"
                                placeholder="Mercedes-Benz, BMW, Peugeot…"
                                {{ $singleMake ? 'readonly' : '' }}
                            >
                            @if($singleMake)
                                <div class="form-text" style="font-size: .75rem;">
                                    <i class="bi bi-lock-fill me-1"></i>Spécialiste {{ $singleMake->name }} uniquement
                                </div>
                            @endif
                            @error('vehicle_make')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="vehicle_model" class="form-label">Modèle</label>
                            <input
                                type="text"
                                id="vehicle_model"
                                name="vehicle_model"
                                class="form-control @error('vehicle_model') is-invalid @enderror"
                                value="{{ old('vehicle_model') }}"
                                placeholder="Corolla, Série 3, 308…"
                            >
                            @error('vehicle_model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-4">
                            <label for="vehicle_year" class="form-label">Année</label>
                            <input
                                type="number"
                                id="vehicle_year"
                                name="vehicle_year"
                                class="form-control @error('vehicle_year') is-invalid @enderror"
                                value="{{ old('vehicle_year') }}"
                                placeholder="2008"
                                min="1950"
                                max="{{ date('Y') + 1 }}"
                            >
                            @error('vehicle_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- === Bloc 3 : Coordonnées === --}}
                <div class="mb-4 p-4 rounded" style="background: var(--ap-bg-card); border: 1px solid var(--ap-border);">
                    <h6 class="fw-bold mb-1 d-flex align-items-center gap-2"
                        style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">
                        <i class="bi bi-person-fill" style="color: var(--ap-accent);"></i>Vos coordonnées
                    </h6>
                    <p class="text-muted mb-3" style="font-size: .83rem;">
                        Un email <strong>ou</strong> un téléphone est requis pour vous recontacter.
                    </p>

                    <div class="mb-3">
                        <label for="contact_name" class="form-label">
                            Votre nom <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="contact_name"
                            name="contact_name"
                            class="form-control @error('contact_name') is-invalid @enderror"
                            value="{{ old('contact_name', auth()->user()?->name) }}"
                            required
                            placeholder="Jean Dupont"
                        >
                        @error('contact_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="contact_email" class="form-label">Email</label>
                            <input
                                type="email"
                                id="contact_email"
                                name="contact_email"
                                class="form-control @error('contact_email') is-invalid @enderror"
                                value="{{ old('contact_email', auth()->user()?->email) }}"
                                placeholder="votre@email.com"
                            >
                            @error('contact_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="contact_phone" class="form-label">Téléphone</label>
                            <input
                                type="tel"
                                id="contact_phone"
                                name="contact_phone"
                                class="form-control @error('contact_phone') is-invalid @enderror"
                                value="{{ old('contact_phone', auth()->user()?->phone) }}"
                                placeholder="+237 6XX XXX XXX"
                            >
                            @error('contact_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-ap-accent btn-lg w-100"
                        style="height: 52px; font-size: 1rem; letter-spacing: .3px;">
                    <i class="bi bi-send-fill me-2"></i>Envoyer ma demande
                </button>

                <p class="text-center text-muted mt-2 mb-0" style="font-size: .78rem;">
                    <i class="bi bi-clock me-1"></i>Réponse sous 24h — formulaire sécurisé
                </p>

            </form>
        </div>
    </div>
</div>
@endsection
