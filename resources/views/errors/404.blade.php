@extends('layouts.public')

@section('title', 'Page introuvable')

@section('content')
<div class="container py-5 text-center" style="min-height: 60vh; display: flex; flex-direction: column; align-items: center; justify-content: center;">
    <div style="font-size: 5rem; font-weight: 900; color: var(--ap-primary); line-height: 1; margin-bottom: 1rem; opacity: .15;">404</div>
    <i class="bi bi-search" style="font-size: 3rem; color: var(--ap-primary); margin-bottom: 1.5rem; display: block;"></i>
    <h1 class="fw-bold mb-2" style="font-size: 1.5rem; color: var(--ap-text);">Page introuvable</h1>
    <p class="mb-4" style="color: var(--ap-text-muted); max-width: 420px;">
        La page que vous cherchez n'existe pas ou a été déplacée.
    </p>
    <div class="d-flex gap-3 justify-content-center flex-wrap">
        <a href="{{ route('home') }}" class="btn btn-ap-accent" style="padding: .65rem 1.5rem;">
            <i class="bi bi-house me-2"></i>Retour à l'accueil
        </a>
        <a href="{{ route('products.index') }}" class="btn" style="padding: .65rem 1.5rem; border: 1.5px solid var(--ap-border); color: var(--ap-text);">
            <i class="bi bi-grid me-2"></i>Voir le catalogue
        </a>
    </div>
</div>
@endsection
