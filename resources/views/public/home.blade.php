@extends('layouts.public')

@section('title', $singleMake ? 'Pièces détachées ' . $singleMake->name . ' d\'occasion' : 'Accueil')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
/* ── Tokens luxe ───────────────────────────────────── */
:root {
    --mb-gold:        #C4A45A;
    --mb-gold-light:  #D9BB7A;
    --mb-gold-pale:   rgba(196,164,90,.12);
    --mb-dark:        #0B0D12;
    --mb-dark2:       #12151C;
    --mb-dark3:       #1A1E28;
    --mb-silver:      #A8B4BF;
    --mb-white:       #F4F0E8;
}

/* ── Hero ──────────────────────────────────────────── */
.mb-hero {
    background: var(--mb-dark);
    color: var(--mb-white);
    padding: 6rem 0 5rem;
    position: relative;
    overflow: hidden;
}

/* Grille diagonale subtile */
.mb-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(45deg, rgba(196,164,90,.04) 1px, transparent 1px),
        linear-gradient(-45deg, rgba(196,164,90,.04) 1px, transparent 1px);
    background-size: 60px 60px;
    pointer-events: none;
}

/* Halo gold en haut à droite */
.mb-hero::after {
    content: '';
    position: absolute;
    top: -180px;
    right: -180px;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(196,164,90,.08) 0%, transparent 70%);
    pointer-events: none;
}

.mb-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    font-family: 'Inter', sans-serif;
    font-size: .72rem;
    font-weight: 600;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--mb-gold);
    margin-bottom: 1.5rem;
}

.mb-eyebrow::before,
.mb-eyebrow::after {
    content: '';
    display: inline-block;
    width: 28px;
    height: 1px;
    background: var(--mb-gold);
    opacity: .6;
}

.mb-hero-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(2.4rem, 5.5vw, 3.8rem);
    font-weight: 700;
    line-height: 1.1;
    letter-spacing: -.5px;
    color: var(--mb-white);
    margin-bottom: 1.25rem;
}

.mb-hero-title .gold { color: var(--mb-gold); }

.mb-hero-subtitle {
    font-family: 'Inter', sans-serif;
    font-size: 1rem;
    font-weight: 300;
    color: var(--mb-silver);
    max-width: 500px;
    line-height: 1.75;
    margin-bottom: 2.5rem;
    letter-spacing: .2px;
}

.mb-search-wrap {
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(196,164,90,.25);
    border-radius: 14px;
    padding: 1.4rem;
    max-width: 620px;
    backdrop-filter: blur(10px);
}

.mb-search-wrap .form-control {
    border: 1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.07);
    color: #fff;
    font-size: .95rem;
    padding: .75rem 1.1rem;
    height: 50px;
    border-radius: 8px 0 0 8px;
    transition: border-color .2s;
}

.mb-search-wrap .form-control::placeholder { color: rgba(255,255,255,.35); }
.mb-search-wrap .form-control:focus {
    outline: none;
    box-shadow: none;
    background: rgba(255,255,255,.1);
    border-color: rgba(196,164,90,.5);
    color: #fff;
}

.mb-search-btn {
    background: var(--mb-gold);
    color: var(--mb-dark);
    border: none;
    height: 50px;
    padding: 0 1.4rem;
    font-weight: 700;
    font-size: .88rem;
    letter-spacing: .5px;
    border-radius: 0 8px 8px 0;
    transition: background .2s;
    white-space: nowrap;
}

.mb-search-btn:hover { background: var(--mb-gold-light); }

.mb-hero-tags { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: 1rem; }
.mb-hero-tag {
    font-size: .78rem;
    padding: .3rem .8rem;
    border-radius: 20px;
    border: 1px solid rgba(196,164,90,.3);
    color: rgba(255,255,255,.6);
    cursor: pointer;
    transition: all .2s;
    font-family: 'Inter', sans-serif;
}
.mb-hero-tag:hover {
    border-color: var(--mb-gold);
    color: var(--mb-gold);
    background: var(--mb-gold-pale);
}

.mb-hero-badges {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    margin-top: 2rem;
    font-size: .8rem;
    color: var(--mb-silver);
    font-family: 'Inter', sans-serif;
}

.mb-hero-badge { display: flex; align-items: center; gap: .4rem; }
.mb-hero-badge i { color: var(--mb-gold); font-size: .85rem; }

/* ── Stats bar ─────────────────────────────────────── */
.mb-stats-bar {
    background: var(--mb-dark2);
    border-top: 1px solid rgba(196,164,90,.15);
    border-bottom: 1px solid rgba(196,164,90,.15);
    padding: 2rem 0;
}

.mb-stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: .5rem 1rem;
}

.mb-stat-number {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--mb-gold);
    line-height: 1;
    margin-bottom: .3rem;
}

.mb-stat-label {
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: var(--mb-silver);
    font-family: 'Inter', sans-serif;
    font-weight: 500;
}

/* ── Section commune ───────────────────────────────── */
.mb-section {
    padding: 5rem 0;
}

.mb-section-label {
    font-family: 'Inter', sans-serif;
    font-size: .7rem;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--mb-gold);
    font-weight: 600;
    margin-bottom: .75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .75rem;
}

.mb-section-label::before,
.mb-section-label::after {
    content: '';
    width: 24px;
    height: 1px;
    background: var(--mb-gold);
    opacity: .5;
}

.mb-section-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(1.6rem, 3.5vw, 2.4rem);
    font-weight: 600;
    color: var(--mb-white);
    margin-bottom: .75rem;
    letter-spacing: -.2px;
}

.mb-section-subtitle {
    font-family: 'Inter', sans-serif;
    font-size: .95rem;
    color: var(--mb-silver);
    font-weight: 300;
}

/* ── Section sombre ────────────────────────────────── */
.mb-dark-section {
    background: var(--mb-dark);
    color: var(--mb-white);
}

.mb-dark-section .mb-section-title { color: var(--mb-white); }

/* ── Modèles ───────────────────────────────────────── */
.mb-model-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.6rem 1rem;
    background: var(--mb-dark3);
    border: 1px solid rgba(196,164,90,.15);
    border-radius: 12px;
    text-decoration: none;
    transition: all .25s ease;
    height: 100%;
    text-align: center;
}

.mb-model-card:hover {
    border-color: var(--mb-gold);
    background: rgba(196,164,90,.06);
    transform: translateY(-3px);
    box-shadow: 0 8px 28px rgba(196,164,90,.12);
}

.mb-model-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 1px solid rgba(196,164,90,.3);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: var(--mb-gold);
    margin-bottom: .8rem;
    transition: all .25s;
}

.mb-model-card:hover .mb-model-icon {
    background: var(--mb-gold);
    color: var(--mb-dark);
    border-color: var(--mb-gold);
}

.mb-model-name {
    font-family: 'Inter', sans-serif;
    font-weight: 600;
    font-size: .88rem;
    color: var(--mb-white);
    margin-bottom: .2rem;
}

.mb-model-year {
    font-family: 'Inter', sans-serif;
    font-size: .72rem;
    color: var(--mb-gold);
    letter-spacing: 1px;
    font-weight: 500;
}

/* ── Catégories ────────────────────────────────────── */
.mb-cat-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.5rem 1rem;
    background: #fff;
    border: 1px solid rgba(0,0,0,.07);
    border-radius: 12px;
    text-decoration: none;
    color: var(--ap-text);
    transition: all .22s;
    text-align: center;
}

.mb-cat-card:hover {
    border-color: var(--mb-gold);
    transform: translateY(-3px);
    box-shadow: 0 6px 22px rgba(196,164,90,.15);
    color: var(--ap-text);
}

.mb-cat-icon {
    width: 52px;
    height: 52px;
    background: #F5F0E5;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: var(--ap-primary);
    margin-bottom: .7rem;
    transition: all .22s;
}

.mb-cat-card:hover .mb-cat-icon {
    background: var(--mb-gold);
    color: #fff;
}

.mb-cat-name {
    font-size: .82rem;
    font-weight: 600;
    font-family: 'Inter', sans-serif;
    letter-spacing: .2px;
}

/* ── Services ──────────────────────────────────────── */
.mb-service-card {
    padding: 2rem;
    background: var(--mb-dark3);
    border: 1px solid rgba(196,164,90,.12);
    border-radius: 14px;
    height: 100%;
    transition: border-color .2s;
}

.mb-service-card:hover { border-color: rgba(196,164,90,.4); }

.mb-service-icon {
    width: 52px;
    height: 52px;
    background: var(--mb-gold-pale);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    color: var(--mb-gold);
    margin-bottom: 1.2rem;
}

.mb-service-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--mb-white);
    margin-bottom: .5rem;
}

.mb-service-text {
    font-family: 'Inter', sans-serif;
    font-size: .88rem;
    color: var(--mb-silver);
    line-height: 1.7;
    margin-bottom: 0;
}

.mb-service-link {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    font-size: .82rem;
    font-weight: 600;
    color: var(--mb-gold);
    text-decoration: none;
    margin-top: .9rem;
    font-family: 'Inter', sans-serif;
    letter-spacing: .3px;
    transition: gap .2s;
}

.mb-service-link:hover { gap: .6rem; color: var(--mb-gold-light); }

/* ── CTA ───────────────────────────────────────────── */
.mb-cta {
    background: linear-gradient(135deg, var(--mb-dark2) 0%, var(--mb-dark3) 100%);
    border-top: 1px solid rgba(196,164,90,.15);
    border-bottom: 1px solid rgba(196,164,90,.15);
    padding: 5rem 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.mb-cta::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 50% 100%, rgba(196,164,90,.08) 0%, transparent 65%);
    pointer-events: none;
}

.mb-cta-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.6rem, 4vw, 2.5rem);
    font-weight: 700;
    color: var(--mb-white);
    margin-bottom: .75rem;
}

.mb-cta-sub {
    font-family: 'Inter', sans-serif;
    font-size: .95rem;
    color: var(--mb-silver);
    margin-bottom: 2rem;
    font-weight: 300;
}

.mb-btn-gold {
    display: inline-flex;
    align-items: center;
    gap: .6rem;
    background: var(--mb-gold);
    color: var(--mb-dark) !important;
    font-weight: 700;
    font-size: .95rem;
    padding: .85rem 2.2rem;
    border-radius: 8px;
    text-decoration: none;
    letter-spacing: .4px;
    font-family: 'Inter', sans-serif;
    transition: all .2s;
    border: none;
}

.mb-btn-gold:hover {
    background: var(--mb-gold-light);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(196,164,90,.3);
}

.mb-btn-outline-gold {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: transparent;
    color: var(--mb-gold) !important;
    font-weight: 600;
    font-size: .88rem;
    padding: .7rem 1.6rem;
    border-radius: 8px;
    text-decoration: none;
    letter-spacing: .3px;
    font-family: 'Inter', sans-serif;
    border: 1.5px solid rgba(196,164,90,.5);
    transition: all .2s;
}

.mb-btn-outline-gold:hover {
    border-color: var(--mb-gold);
    background: var(--mb-gold-pale);
}

/* ── Séparateur or ─────────────────────────────────── */
.mb-divider {
    width: 40px;
    height: 2px;
    background: var(--mb-gold);
    margin: 0 auto 1.25rem;
}

/* ── Override ap-hero global ───────────────────────── */
.mb-override-hero { all: unset; }
</style>
@endpush

@section('content')

{{-- ════════════════════════════════════════════
     HERO
════════════════════════════════════════════ --}}
<section class="mb-hero">
    <div class="container position-relative" style="z-index:1;">
        <div class="row align-items-center">
            <div class="col-lg-8">

                <div class="mb-eyebrow">
                    @if($singleMake)
                        Spécialiste {{ $singleMake->name }}
                    @else
                        Pièces automobiles d'occasion
                    @endif
                </div>

                <h1 class="mb-hero-title">
                    @if($singleMake)
                        Pièces détachées<br>
                        <span class="gold">{{ $singleMake->name }}</span><br>
                        d'occasion
                    @else
                        Trouvez la pièce<br>
                        qu'il vous faut
                    @endif
                </h1>

                <p class="mb-hero-subtitle">
                    @if($singleMake)
                        L'expertise {{ $singleMake->name }} — moteur, freinage, carrosserie,
                        électronique. Chaque pièce inspectée, classée, disponible.
                    @else
                        Pièces d'occasion vérifiées, classées par état et compatibilité.
                        Fiables, abordables, disponibles maintenant.
                    @endif
                </p>

                <div class="mb-search-wrap">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="d-flex">
                            <input
                                type="text"
                                class="form-control"
                                name="q"
                                placeholder="Alternateur, amortisseur, boîte de vitesse…"
                                autocomplete="off"
                            >
                            <button type="submit" class="mb-search-btn">
                                <i class="bi bi-search me-1"></i>Rechercher
                            </button>
                        </div>
                        <div class="mb-hero-tags">
                            <span class="mb-hero-tag" onclick="this.closest('form').q.value='alternateur'; this.closest('form').submit();">Alternateur</span>
                            <span class="mb-hero-tag" onclick="this.closest('form').q.value='amortisseur'; this.closest('form').submit();">Amortisseur</span>
                            <span class="mb-hero-tag" onclick="this.closest('form').q.value='moteur'; this.closest('form').submit();">Moteur</span>
                            <span class="mb-hero-tag" onclick="this.closest('form').q.value='radiateur'; this.closest('form').submit();">Radiateur</span>
                            <span class="mb-hero-tag" onclick="this.closest('form').q.value='boîte vitesse'; this.closest('form').submit();">Boîte de vitesse</span>
                        </div>
                    </form>
                </div>

                <div class="mb-hero-badges">
                    <span class="mb-hero-badge"><i class="bi bi-check-circle-fill"></i>Pièces inspectées</span>
                    <span class="mb-hero-badge"><i class="bi bi-clock-fill"></i>Stock en temps réel</span>
                    @if($singleMake)
                        <span class="mb-hero-badge"><i class="bi bi-star-fill"></i>Expert {{ $singleMake->name }}</span>
                    @endif
                </div>

            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════
     STATS BAR
════════════════════════════════════════════ --}}
<div class="mb-stats-bar">
    <div class="container">
        <div class="row g-0 justify-content-center">
            @if($singleMake && $models->count())
            <div class="col-6 col-md-3">
                <div class="mb-stat-item">
                    <div class="mb-stat-number">{{ $models->count() }}</div>
                    <div class="mb-stat-label">Modèles couverts</div>
                </div>
            </div>
            @endif
            <div class="col-6 col-md-3">
                <div class="mb-stat-item">
                    <div class="mb-stat-number">7</div>
                    <div class="mb-stat-label">Catégories</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="mb-stat-item">
                    <div class="mb-stat-number">100%</div>
                    <div class="mb-stat-label">Pièces inspectées</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="mb-stat-item">
                    <div class="mb-stat-number">24h</div>
                    <div class="mb-stat-label">Délai de réponse</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════
     MODÈLES (mode marque unique)
════════════════════════════════════════════ --}}
@if($singleMake && $models->count())
<section class="mb-section mb-dark-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="mb-section-label">Compatibilité véhicule</div>
            <h2 class="mb-section-title">Modèles {{ $singleMake->name }}</h2>
            <div class="mb-divider"></div>
            <p class="mb-section-subtitle">Sélectionnez votre modèle pour afficher les pièces compatibles</p>
        </div>

        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3 mb-4">
            @foreach($models as $model)
                <div class="col">
                    <a href="{{ route('products.index', ['model' => $model->id]) }}"
                       class="mb-model-card">
                        <div class="mb-model-icon">
                            <i class="bi bi-car-front"></i>
                        </div>
                        <span class="mb-model-name">{{ $model->name }}</span>
                        @if($model->year_start)
                            <span class="mb-model-year">depuis {{ $model->year_start }}</span>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>

        <div class="text-center">
            <a href="{{ route('products.index') }}" class="mb-btn-outline-gold">
                <i class="bi bi-grid"></i> Voir tout le catalogue
            </a>
        </div>
    </div>
</section>
@endif

{{-- ════════════════════════════════════════════
     CATÉGORIES
════════════════════════════════════════════ --}}
<section class="mb-section" style="background: var(--ap-bg);">
    <div class="container">
        <div class="text-center mb-5">
            <div class="mb-section-label" style="color: var(--ap-primary); --mb-gold: var(--ap-primary);">Parcourir</div>
            <h2 class="mb-section-title" style="color: var(--ap-text);">
                Toutes les pièces {{ $singleMake ? $singleMake->name : 'auto' }}
            </h2>
            <div class="mb-divider" style="background: var(--ap-primary);"></div>
            <p class="mb-section-subtitle" style="color: var(--ap-text-muted);">
                Moteur, freinage, carrosserie, électronique et plus encore
            </p>
        </div>

        @php
        $cats = [
            ['icon' => 'bi-cpu-fill',             'label' => 'Moteur',           'q' => 'moteur'],
            ['icon' => 'bi-disc-fill',             'label' => 'Freinage',         'q' => 'frein'],
            ['icon' => 'bi-arrows-move',           'label' => 'Suspension',       'q' => 'suspension'],
            ['icon' => 'bi-lightning-charge-fill', 'label' => 'Électrique',       'q' => 'électrique'],
            ['icon' => 'bi-wind',                  'label' => 'Refroidissement',  'q' => 'radiateur'],
            ['icon' => 'bi-car-front',             'label' => 'Carrosserie',      'q' => 'carrosserie'],
            ['icon' => 'bi-gear-wide-connected',   'label' => 'Transmission',     'q' => 'boîte'],
            ['icon' => 'bi-eye-fill',              'label' => 'Optiques',         'q' => 'phare'],
            ['icon' => 'bi-wrench-adjustable',     'label' => 'Autres',           'q' => ''],
        ];
        @endphp

        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3">
            @foreach($cats as $cat)
                <div class="col">
                    <a href="{{ route('products.index', ['q' => $cat['q']]) }}"
                       class="mb-cat-card">
                        <div class="mb-cat-icon">
                            <i class="bi {{ $cat['icon'] }}"></i>
                        </div>
                        <span class="mb-cat-name">{{ $cat['label'] }}</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════
     SERVICES
════════════════════════════════════════════ --}}
<section class="mb-section mb-dark-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="mb-section-label">Notre expertise</div>
            <h2 class="mb-section-title">Pourquoi nous choisir</h2>
            <div class="mb-divider"></div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="mb-service-card">
                    <div class="mb-service-icon"><i class="bi bi-box-seam-fill"></i></div>
                    <h5 class="mb-service-title">Catalogue complet</h5>
                    <p class="mb-service-text">
                        Recherchez par nom, référence OEM ou modèle
                        {{ $singleMake ? $singleMake->name : 'de véhicule' }}.
                        Stock mis à jour en temps réel.
                    </p>
                    <a href="{{ route('products.index') }}" class="mb-service-link">
                        Voir le catalogue <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-service-card">
                    <div class="mb-service-icon"><i class="bi bi-shield-check-fill"></i></div>
                    <h5 class="mb-service-title">Pièces inspectées</h5>
                    <p class="mb-service-text">
                        Chaque pièce est contrôlée et classée : bon état, état correct ou
                        reconditionnée. Vous savez exactement ce que vous achetez.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-service-card">
                    <div class="mb-service-icon"><i class="bi bi-chat-dots-fill"></i></div>
                    <h5 class="mb-service-title">Pièce introuvable ?</h5>
                    <p class="mb-service-text">
                        Soumettez une demande — notre équipe recherche la pièce
                        {{ $singleMake ? $singleMake->name : '' }} dont vous avez besoin
                        et vous recontacte sous 24h.
                    </p>
                    <a href="{{ route('part-requests.create') }}" class="mb-service-link">
                        Faire une demande <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════
     CTA
════════════════════════════════════════════ --}}
<section class="mb-cta">
    <div class="container position-relative" style="z-index:1;">
        <h2 class="mb-cta-title">
            Vous ne trouvez pas votre pièce {{ $singleMake ? $singleMake->name : '' }} ?
        </h2>
        <p class="mb-cta-sub">
            Notre équipe s'en charge — formulaire rapide, réponse sous 24h.
        </p>
        <a href="{{ route('part-requests.create') }}" class="mb-btn-gold">
            <i class="bi bi-search"></i> Je recherche une pièce
        </a>
    </div>
</section>

@endsection
