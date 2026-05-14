@extends('layouts.public')

@section('title', 'Demande de pièce')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">

            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Catalogue</a></li>
                    <li class="breadcrumb-item active">Demande de pièce</li>
                </ol>
            </nav>

            <h2 class="mb-1">Je recherche une pièce</h2>
            <p class="text-muted mb-4">Remplissez ce formulaire et nous rechercherons la pièce pour vous.</p>

            <form action="{{ route('part-requests.store') }}" method="POST" novalidate>
                @csrf

                <div class="card shadow-sm mb-4">
                    <div class="card-header fw-semibold bg-light">
                        <i class="bi bi-gear me-1"></i>Pièce recherchée
                    </div>
                    <div class="card-body">
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
                                placeholder="Ex : Alternateur, Plaquettes de frein avant…"
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
                                placeholder="Référence OEM ou autre"
                            >
                            @error('reference')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Détails supplémentaires</label>
                            <textarea
                                id="description"
                                name="description"
                                class="form-control @error('description') is-invalid @enderror"
                                rows="3"
                                placeholder="Décrivez votre besoin, l'état souhaité, l'urgence…"
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header fw-semibold bg-light">
                        <i class="bi bi-car-front me-1"></i>Véhicule concerné
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label for="vehicle_make" class="form-label">Marque</label>
                                <input
                                    type="text"
                                    id="vehicle_make"
                                    name="vehicle_make"
                                    class="form-control @error('vehicle_make') is-invalid @enderror"
                                    value="{{ old('vehicle_make') }}"
                                    placeholder="Toyota, BMW…"
                                >
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
                                    placeholder="Corolla, Série 3…"
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
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header fw-semibold bg-light">
                        <i class="bi bi-person me-1"></i>Vos coordonnées
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            Un email <strong>ou</strong> un téléphone est obligatoire pour vous recontacter.
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
                </div>

                <button type="submit" class="btn btn-dark btn-lg w-100">
                    <i class="bi bi-send me-1"></i>Envoyer ma demande
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
