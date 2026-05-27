@extends('layouts.public')

@section('title', 'Accueil')

@section('content')

{{-- ======== HERO ======== --}}
<section class="ap-hero">
    <div class="container position-relative" style="z-index: 1;">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <p class="hero-eyebrow">
                    <i class="bi bi-geo-alt-fill me-1"></i>Pièces disponibles maintenant
                </p>
                <h1 class="hero-title">
                    Trouvez la pièce auto<br>
                    qu'il vous faut <span class="highlight">rapidement.</span>
                </h1>
                <p class="hero-subtitle">
                    Pièces détachées d'occasion vérifiées, classées par état et compatibilité véhicule.
                    Fiables et abordables.
                </p>

                {{-- Search box --}}
                <div class="hero-search-box">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="d-flex mb-2">
                            <input
                                type="text"
                                class="form-control"
                                name="q"
                                placeholder="Rechercher : alternateur, pare-choc, boîte de vitesse…"
                                autocomplete="off"
                                autofocus
                            >
                            <button type="submit" class="btn-search-hero px-3">
                                <i class="bi bi-search me-1"></i>Rechercher
                            </button>
                        </div>
                        <div class="hero-tags">
                            <span class="hero-tag" onclick="this.closest('form').q.value='alternateur'; this.closest('form').submit();">Alternateur</span>
                            <span class="hero-tag" onclick="this.closest('form').q.value='pare-choc'; this.closest('form').submit();">Pare-choc</span>
                            <span class="hero-tag" onclick="this.closest('form').q.value='boîte vitesse'; this.closest('form').submit();">Boîte de vitesse</span>
                            <span class="hero-tag" onclick="this.closest('form').q.value='moteur'; this.closest('form').submit();">Moteur</span>
                            <span class="hero-tag" onclick="this.closest('form').q.value='radiateur'; this.closest('form').submit();">Radiateur</span>
                        </div>
                    </form>
                </div>

                <div class="mt-3 d-flex gap-3 flex-wrap" style="font-size: .82rem; color: rgba(255,255,255,.65);">
                    <span><i class="bi bi-check2 me-1" style="color: var(--ap-accent-light);"></i>Pièces inspectées</span>
                    <span><i class="bi bi-check2 me-1" style="color: var(--ap-accent-light);"></i>Stock mis à jour</span>
                    <span><i class="bi bi-check2 me-1" style="color: var(--ap-accent-light);"></i>Compatible multi-marques</span>
                </div>
            </div>

            <div class="col-lg-5 d-none d-lg-flex justify-content-center">
                <div style="font-size: 9rem; opacity: .12; user-select: none; line-height: 1;">
                    <i class="bi bi-car-front-fill"></i>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ======== TRUST BAR ======== --}}
<div class="ap-trust-bar">
    <div class="container">
        <div class="row g-2 d-flex ap-trust-row">
            <div class="col-6 col-md-3">
                <div class="ap-trust-item">
                    <i class="bi bi-shield-check trust-icon"></i>
                    <div>
                        <div class="trust-label">Pièces vérifiées</div>
                        <div class="trust-desc">Inspectées avant mise en vente</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="ap-trust-item">
                    <i class="bi bi-box-seam trust-icon"></i>
                    <div>
                        <div class="trust-label">Stock disponible</div>
                        <div class="trust-desc">Disponibilité en temps réel</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="ap-trust-item">
                    <i class="bi bi-headset trust-icon"></i>
                    <div>
                        <div class="trust-label">Assistance</div>
                        <div class="trust-desc">On vous aide à trouver</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="ap-trust-item">
                    <i class="bi bi-currency-exchange trust-icon"></i>
                    <div>
                        <div class="trust-label">Prix abordables</div>
                        <div class="trust-desc">Pièces d'occasion fiables</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ======== CATÉGORIES ======== --}}
<section class="ap-section">
    <div class="container">
        <div class="ap-section-header">
            <div class="section-tag">Parcourir par catégorie</div>
            <h2>Toutes les pièces auto</h2>
            <p>Moteur, freinage, carrosserie, électronique et plus encore.</p>
        </div>

        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
            @php
            $cats = [
                ['icon' => 'bi-cpu-fill',          'label' => 'Moteur',       'q' => 'moteur'],
                ['icon' => 'bi-disc-fill',          'label' => 'Freinage',     'q' => 'frein'],
                ['icon' => 'bi-arrows-move',        'label' => 'Suspension',   'q' => 'suspension'],
                ['icon' => 'bi-lightning-charge-fill','label'=>'Électrique',   'q' => 'électrique'],
                ['icon' => 'bi-wind',               'label' => 'Refroidissement','q'=>'radiateur'],
                ['icon' => 'bi-car-front',          'label' => 'Carrosserie',  'q' => 'carrosserie'],
                ['icon' => 'bi-gear-wide-connected','label' => 'Transmission', 'q' => 'boîte'],
                ['icon' => 'bi-eye-fill',           'label' => 'Optiques',     'q' => 'phare'],
                ['icon' => 'bi-wrench-adjustable',  'label' => 'Autres',       'q' => ''],
            ];
            @endphp

            @foreach($cats as $cat)
                <div class="col">
                    <a href="{{ route('products.index', ['q' => $cat['q']]) }}"
                       class="ap-cat-card">
                        <div class="cat-icon-wrap">
                            <i class="bi {{ $cat['icon'] }}"></i>
                        </div>
                        <span class="cat-name">{{ $cat['label'] }}</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ======== SERVICES ======== --}}
<section style="background: var(--ap-bg-card); padding: 3.5rem 0; border-top: 1px solid var(--ap-border);">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="d-flex gap-3">
                    <div style="width: 48px; height: 48px; background: #EEE5D3; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.3rem; color: var(--ap-primary);">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Catalogue complet</h6>
                        <p class="text-muted small mb-0">
                            Recherchez par nom, référence, marque ou modèle de véhicule.
                        </p>
                        <a href="{{ route('products.index') }}"
                           class="small fw-semibold mt-1 d-inline-block text-decoration-none"
                           style="color: var(--ap-accent);">
                            Voir le catalogue <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex gap-3">
                    <div style="width: 48px; height: 48px; background: #E8F4EC; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.3rem; color: var(--ap-success);">
                        <i class="bi bi-shield-check-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Pièces inspectées</h6>
                        <p class="text-muted small mb-0">
                            Chaque pièce est contrôlée et classée : bon état, correct ou reconditionné.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex gap-3">
                    <div style="width: 48px; height: 48px; background: var(--ap-bg-card)7ed; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.3rem; color: var(--ap-accent);">
                        <i class="bi bi-chat-dots-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Pièce introuvable ?</h6>
                        <p class="text-muted small mb-0">
                            Faites une demande, nous rechercherons la pièce dont vous avez besoin.
                        </p>
                        <a href="{{ route('part-requests.create') }}"
                           class="small fw-semibold mt-1 d-inline-block text-decoration-none"
                           style="color: var(--ap-accent);">
                            Soumettre une demande <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ======== CTA SECTION ======== --}}
<section class="ap-section">
    <div class="container">
        <div class="ap-cta-section">
            <h2 class="mb-2">Vous ne trouvez pas votre pièce ?</h2>
            <p>
                Soumettez une demande de recherche. Notre équipe s'occupe de retrouver la pièce dont vous avez besoin.
            </p>
            <a href="{{ route('part-requests.create') }}"
               class="btn btn-lg"
               style="background: var(--ap-accent); color: #fff; font-weight: 700; border-radius: 8px; padding: .7rem 2rem;">
                <i class="bi bi-search me-2"></i>Je recherche une pièce
            </a>
            <div class="mt-3 small" style="color: rgba(255,255,255,.55);">
                Formulaire rapide — réponse sous 24h
            </div>
        </div>
    </div>
</section>

@endsection
