@extends('layouts.public')

@section('title', 'Erreur serveur')

@section('content')
<div class="container py-5 text-center" style="min-height: 60vh; display: flex; flex-direction: column; align-items: center; justify-content: center;">
    <div style="font-size: 5rem; font-weight: 900; color: var(--ap-danger); line-height: 1; margin-bottom: 1rem; opacity: .15;">500</div>
    <i class="bi bi-exclamation-triangle" style="font-size: 3rem; color: var(--ap-danger); margin-bottom: 1.5rem; display: block;"></i>
    <h1 class="fw-bold mb-2" style="font-size: 1.5rem; color: var(--ap-text);">Erreur serveur</h1>
    <p class="mb-4" style="color: var(--ap-text-muted); max-width: 420px;">
        Une erreur inattendue s'est produite. Notre équipe a été notifiée. Veuillez réessayer dans quelques instants.
    </p>
    <a href="{{ route('home') }}" class="btn btn-ap-accent" style="padding: .65rem 1.5rem;">
        <i class="bi bi-house me-2"></i>Retour à l'accueil
    </a>
</div>
@endsection
