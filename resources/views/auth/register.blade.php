@extends('layouts.public')

@section('title', 'Créer un compte')

@section('content')
<div class="ap-auth-wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="ap-auth-card">

                    {{-- Logo --}}
                    <a href="{{ route('home') }}" class="auth-logo mb-4 d-block">
                        <i class="bi bi-gear-fill brand-icon me-1"></i>PALERME AUTO PRO
                    </a>

                    <h2 class="auth-title">Créer un compte</h2>
                    <p class="auth-subtitle">Rejoignez PALERME AUTO PRO pour passer commande facilement.</p>

                    <form action="{{ route('register') }}" method="POST" novalidate>
                        @csrf

                        {{-- Nom --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Nom complet <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                required
                                autocomplete="name"
                                placeholder="Jean Dupont"
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Adresse email <span class="text-danger">*</span>
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                placeholder="votre@email.com"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Téléphone --}}
                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}"
                                autocomplete="tel"
                                placeholder="+33 6 00 00 00 00"
                            >
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Mot de passe --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Mot de passe <span class="text-danger">*</span>
                            </label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                required
                                autocomplete="new-password"
                                placeholder="8 caractères minimum"
                            >
                            <div class="form-text" style="font-size: .78rem; color: var(--ap-text-muted);">
                                <i class="bi bi-info-circle me-1"></i>8 caractères minimum.
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Confirmation --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                Confirmer le mot de passe <span class="text-danger">*</span>
                            </label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control"
                                required
                                autocomplete="new-password"
                                placeholder="••••••••"
                            >
                        </div>

                        <button type="submit" class="btn-submit">
                            Créer mon compte
                        </button>
                    </form>

                    <div class="mt-4 pt-3 text-center" style="border-top: 1px solid var(--ap-border);">
                        <span class="text-muted" style="font-size: .88rem;">Déjà un compte ?</span>
                        <a href="{{ route('login') }}"
                           class="fw-semibold text-decoration-none ms-1"
                           style="color: var(--ap-accent);">
                            Se connecter
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
