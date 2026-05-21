@extends('layouts.admin')

@section('title', 'Mon profil')

@section('content')

<div style="max-width: 640px;">

    {{-- Avatar --}}
    <div class="ap-card p-4 mb-4 d-flex align-items-center gap-3">
        <div style="width: 60px; height: 60px; background: var(--ap-primary); border-radius: 50%;
                    display: flex; align-items: center; justify-content: center;
                    font-size: 1.4rem; font-weight: 700; color: #fff; flex-shrink: 0; letter-spacing: 1px;">
            {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($user->name, ' ') ?: $user->name, 1, 1)) }}
        </div>
        <div>
            <div class="fw-bold" style="font-size: 1rem; color: var(--ap-text);">{{ $user->name }}</div>
            <div style="font-size: .82rem; color: var(--ap-text-muted);">{{ $user->email }}</div>
            <span class="ap-badge" style="background: #E8F4EC; color: var(--ap-success); margin-top: .3rem; display: inline-block;">
                <i class="bi bi-shield-fill-check me-1"></i>Administrateur
            </span>
        </div>
    </div>

    <div class="ap-card p-4">
        <form action="{{ route('admin.profile.update') }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            {{-- Informations personnelles --}}
            <h6 class="fw-bold mb-3" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted); border-bottom: 1px solid var(--ap-border); padding-bottom: .5rem;">
                <i class="bi bi-person me-1"></i>Informations personnelles
            </h6>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size: .85rem;">Nom complet <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name) }}" placeholder="Prénom Nom" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size: .85rem;">Adresse e-mail <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $user->email) }}" placeholder="admin@exemple.fr" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size: .85rem;">Téléphone</label>
                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                       value="{{ old('phone', $user->phone) }}" placeholder="+33 6 12 34 56 78">
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Mot de passe --}}
            <h6 class="fw-bold mb-3" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted); border-bottom: 1px solid var(--ap-border); padding-bottom: .5rem;">
                <i class="bi bi-lock me-1"></i>Changer le mot de passe
                <span style="font-weight: 400; font-size: .75rem; text-transform: none; color: var(--ap-text-muted);">(laisser vide pour ne pas changer)</span>
            </h6>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size: .85rem;">Nouveau mot de passe</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                       placeholder="8 caractères minimum" autocomplete="new-password">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size: .85rem;">Confirmer le nouveau mot de passe</label>
                <input type="password" name="password_confirmation" class="form-control"
                       placeholder="Répétez le nouveau mot de passe" autocomplete="new-password">
            </div>

            {{-- Confirmation --}}
            <div class="p-3 rounded mb-4" style="background: #FEF3C7; border: 1px solid #FDE68A;">
                <h6 class="fw-bold mb-2" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: #92400e;">
                    <i class="bi bi-shield-lock me-1"></i>Confirmation de sécurité
                </h6>
                <label class="form-label" style="font-size: .85rem; color: #78350f;">
                    Mot de passe actuel <span class="text-danger">*</span>
                </label>
                <input type="password" name="current_password"
                       class="form-control @error('current_password') is-invalid @enderror"
                       placeholder="Votre mot de passe actuel" autocomplete="current-password" required>
                @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="mt-1" style="font-size: .78rem; color: #92400e;">Requis pour valider toute modification.</div>
            </div>

            <button type="submit" class="btn btn-ap-accent w-100" style="height: 48px; font-size: .95rem; font-weight: 700;">
                <i class="bi bi-check-lg me-2"></i>Enregistrer les modifications
            </button>
        </form>
    </div>

</div>

@endsection
