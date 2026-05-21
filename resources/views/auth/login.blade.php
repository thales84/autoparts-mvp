@extends('layouts.public')

@section('title', 'Connexion')

@section('content')
<div class="ap-auth-wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-7 col-lg-5 col-xl-4">
                <div class="ap-auth-card">

                    {{-- Logo --}}
                    <a href="{{ route('home') }}" class="auth-logo mb-4 d-block">
                        <i class="bi bi-gear-fill brand-icon me-1"></i>PALERME AUTO PRO
                    </a>

                    <h2 class="auth-title">Connexion</h2>
                    <p class="auth-subtitle">Accédez à votre compte pour passer commande.</p>

                    <form action="{{ route('login') }}" method="POST" novalidate>
                        @csrf

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                autofocus
                                placeholder="votre@email.com"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Mot de passe --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Remember --}}
                        <div class="mb-4 d-flex align-items-center gap-2">
                            <input type="checkbox" class="form-check-input m-0" id="remember" name="remember">
                            <label class="form-check-label text-muted" for="remember" style="font-size: .85rem; cursor: pointer;">
                                Se souvenir de moi
                            </label>
                        </div>

                        <button type="submit" class="btn-submit">
                            Se connecter
                        </button>
                    </form>

                    <div class="mt-4 pt-3 text-center" style="border-top: 1px solid var(--ap-border);">
                        <span class="text-muted" style="font-size: .88rem;">Pas encore de compte ?</span>
                        <a href="{{ route('register') }}"
                           class="fw-semibold text-decoration-none ms-1"
                           style="color: var(--ap-accent);">
                            Créer un compte
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
