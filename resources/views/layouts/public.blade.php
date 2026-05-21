<!DOCTYPE html>
@php
    use App\Models\Setting;
    $seoTitle       = Setting::get('seo_title',       config('app.name') . ' — Pièces auto d\'occasion');
    $seoDescription = Setting::get('seo_description', 'Pièces détachées automobiles d\'occasion vérifiées, fiables et abordables.');
    $seoKeywords    = Setting::get('seo_keywords',    '');
    $seoRobots      = Setting::get('seo_robots',      'index,follow');
    $seoOgTitle     = Setting::get('seo_og_title',    '') ?: $seoTitle;
    $seoOgDesc      = Setting::get('seo_og_description', '') ?: $seoDescription;
    $seoOgImage     = Setting::get('seo_og_image',    '');
    $seoGa          = Setting::get('seo_google_analytics', '');
    $seoGsv         = Setting::get('seo_google_site_verification', '');
@endphp
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('seo_title', $seoTitle)</title>

    {{-- Méta de base --}}
    <meta name="description" content="@yield('seo_description', $seoDescription)">
    @if($seoKeywords)
    <meta name="keywords" content="{{ $seoKeywords }}">
    @endif
    <meta name="robots" content="{{ $seoRobots }}">
    @if($seoGsv)
    <meta name="google-site-verification" content="{{ $seoGsv }}">
    @endif

    {{-- Canonical --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:type"        content="website">
    <meta property="og:site_name"   content="{{ config('app.name') }}">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:title"       content="@yield('seo_title', $seoOgTitle)">
    <meta property="og:description" content="@yield('seo_description', $seoOgDesc)">
    @if($seoOgImage)
    <meta property="og:image"       content="{{ asset('uploads/seo/' . $seoOgImage) }}">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="@yield('seo_title', $seoOgTitle)">
    <meta name="twitter:description" content="@yield('seo_description', $seoOgDesc)">
    @if($seoOgImage)
    <meta name="twitter:image"       content="{{ asset('uploads/seo/' . $seoOgImage) }}">
    @endif

    {{-- Google Analytics 4 --}}
    @if($seoGa)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $seoGa }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $seoGa }}');
    </script>
    @endif

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    @stack('styles')
</head>
<body>

{{-- ======== NAVBAR ======== --}}
<nav class="navbar ap-navbar navbar-expand-lg">
    <div class="container">

        {{-- Logo --}}
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="bi bi-gear-fill brand-icon me-1"></i>PALERME AUTO PRO
        </a>

        {{-- Mobile : panier + toggler --}}
        <div class="d-flex align-items-center gap-2 ms-auto d-lg-none">
            <a href="{{ route('cart.index') }}" class="btn-cart position-relative">
                <i class="bi bi-cart3"></i>
                @if(session()->has('cart') && count(session('cart', [])) > 0)
                    <span class="cart-badge">{{ count(session('cart', [])) }}</span>
                @endif
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        {{-- Collapse --}}
        <div class="collapse navbar-collapse" id="navMain">

            {{-- Nav links --}}
            <ul class="navbar-nav me-3 mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                       href="{{ route('home') }}">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                       href="{{ route('products.index') }}">Catalogue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('part-requests.*') ? 'active' : '' }}"
                       href="{{ route('part-requests.create') }}">Demande de pièce</a>
                </li>
            </ul>

            {{-- Barre de recherche --}}
            <form class="ap-search-form d-flex flex-grow-1 me-3"
                  action="{{ route('products.index') }}" method="GET">
                <input type="text"
                       class="form-control"
                       name="q"
                       placeholder="Rechercher une pièce, référence, marque…"
                       value="{{ request('q') }}"
                       autocomplete="off">
                <button type="submit" class="btn-search">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            {{-- Desktop : panier + auth --}}
            <div class="d-none d-lg-flex align-items-center gap-2">
                <a href="{{ route('cart.index') }}" class="btn-cart position-relative" title="Panier">
                    <i class="bi bi-cart3"></i>
                    @if(session()->has('cart') && count(session('cart', [])) > 0)
                        <span class="cart-badge">{{ count(session('cart', [])) }}</span>
                    @endif
                </a>

                @auth
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-fill me-1"></i>{{ Str::limit(Auth::user()->name, 14) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(Auth::user()->role === 'admin')
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>Administration
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('account.profile') }}">
                                    <i class="bi bi-person-circle me-2"></i>Mon profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('account.orders') }}">
                                    <i class="bi bi-receipt me-2"></i>Mes commandes
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-outline-nav">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-ap-accent btn btn-sm">Inscription</a>
                @endauth
            </div>

            {{-- Mobile : liens auth --}}
            @guest
                <div class="d-flex d-lg-none gap-2 mt-2 mb-1">
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm flex-grow-1">Connexion</a>
                    <a href="{{ route('register') }}" class="btn btn-sm flex-grow-1 btn-ap-accent">Inscription</a>
                </div>
            @endguest

        </div>{{-- /.collapse --}}
    </div>
</nav>

{{-- ======== FLASH MESSAGES ======== --}}
@if(session('success'))
    <div class="container mt-3">
        <div class="ap-flash ap-flash-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="container mt-3">
        <div class="ap-flash ap-flash-error alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

{{-- ======== CONTENT ======== --}}
<main>
    @yield('content')
</main>

{{-- ======== FOOTER ======== --}}
<footer class="ap-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-sm-12 col-md-4">
                <div class="footer-brand mb-2">
                    <i class="bi bi-gear-fill brand-icon me-1"></i>PALERME AUTO PRO
                </div>
                <p class="small mb-2" style="color: rgba(255,255,255,.6); max-width: 280px;">
                    Pièces détachées automobiles d'occasion — vérifiées, fiables et abordables.
                </p>
                <span class="badge" style="background: rgba(255,255,255,.1); color: rgba(255,255,255,.8); font-size: .75rem;">
                    <i class="bi bi-shield-check me-1"></i>Pièces vérifiées
                </span>
            </div>
            <div class="col-6 col-md-2">
                <h6>Navigation</h6>
                <a href="{{ route('home') }}">Accueil</a>
                <a href="{{ route('products.index') }}">Catalogue</a>
                <a href="{{ route('part-requests.create') }}">Demande de pièce</a>
            </div>
            <div class="col-6 col-md-3">
                <h6>Mon compte</h6>
                @auth
                    <a href="{{ route('account.orders') }}">Mes commandes</a>
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}">Administration</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                style="background: none; border: none; padding: 0; color: rgba(255,255,255,.75); font-size: .9rem; cursor: pointer; display: block; margin-bottom: .4rem;">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Connexion</a>
                    <a href="{{ route('register') }}">Créer un compte</a>
                @endauth
            </div>
            <div class="col-12 col-md-3">
                <h6>Besoin d'aide ?</h6>
                <a href="{{ route('part-requests.create') }}">
                    <i class="bi bi-search me-1"></i>Pièce introuvable ?
                </a>
                <a href="{{ route('products.index') }}">
                    <i class="bi bi-grid me-1"></i>Voir tout le catalogue
                </a>
            </div>
        </div>

        <div class="footer-bottom text-center">
            © {{ date('Y') }} PALERME AUTO PRO — Pièces détachées automobiles d'occasion
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

{{-- ======== MODAL PANIER ======== --}}
<div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content" style="border: none; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,.18);">

            {{-- En-tête --}}
            <div style="background: var(--ap-primary); padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: .75rem;">
                <div style="width: 36px; height: 36px; background: #22c55e; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; animation: cartCheckPop .35s cubic-bezier(.175,.885,.32,1.275);">
                    <i class="bi bi-check-lg" style="color: #fff; font-size: 1.1rem; font-weight: 700;"></i>
                </div>
                <div>
                    <div style="color: #fff; font-weight: 700; font-size: 1rem; line-height: 1.2;">Ajouté au panier !</div>
                    <div style="color: rgba(255,255,255,.65); font-size: .78rem;" id="cartModalQty"></div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            {{-- Produit --}}
            <div style="background: var(--ap-bg-card); padding: 1.25rem 1.5rem; display: flex; gap: 1rem; align-items: center; border-bottom: 1px solid var(--ap-border);">
                <div id="cartModalImg"
                     style="width: 72px; height: 72px; border-radius: 10px; overflow: hidden; flex-shrink: 0; background: #EDE8DF; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-image" style="color: #94a3b8; font-size: 1.4rem;"></i>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div id="cartModalName" style="font-weight: 700; font-size: .95rem; color: var(--ap-text); line-height: 1.3; margin-bottom: .3rem;"></div>
                    <div id="cartModalPrice" style="font-size: .88rem; color: var(--ap-primary); font-weight: 600;"></div>
                </div>
            </div>

            {{-- Récap panier --}}
            <div style="background: var(--ap-bg-card); padding: .9rem 1.5rem; border-bottom: 1px solid var(--ap-border); display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: .82rem; color: var(--ap-text-muted);">
                    <i class="bi bi-cart3 me-1"></i>
                    <span id="cartModalCount"></span> article<span id="cartModalCountPlural"></span> dans votre panier
                </span>
                <span style="font-weight: 700; font-size: .95rem; color: var(--ap-primary);" id="cartModalTotal"></span>
            </div>

            {{-- Actions --}}
            <div style="background: var(--ap-bg); padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: .6rem;">
                <a href="{{ route('cart.index') }}"
                   class="btn btn-ap-accent w-100"
                   style="height: 48px; font-size: .95rem; display: flex; align-items: center; justify-content: center; gap: .5rem; font-weight: 700; border-radius: 10px;">
                    <i class="bi bi-cart-check-fill"></i>Voir mon panier & commander
                </a>
                <button type="button"
                        class="btn w-100"
                        data-bs-dismiss="modal"
                        style="height: 44px; font-size: .88rem; border: 1.5px solid var(--ap-border); color: var(--ap-text-muted); border-radius: 10px; background: var(--ap-bg-card);">
                    <i class="bi bi-arrow-left me-1"></i>Continuer mes achats
                </button>
            </div>

        </div>
    </div>
</div>

<style>
@keyframes cartCheckPop {
    0%   { transform: scale(0); opacity: 0; }
    70%  { transform: scale(1.15); }
    100% { transform: scale(1); opacity: 1; }
}
</style>

<script>
(function () {
    const modal    = new bootstrap.Modal(document.getElementById('cartModal'));
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrf     = csrfMeta ? csrfMeta.getAttribute('content') : '';

    function updateBadges(count) {
        document.querySelectorAll('.cart-badge').forEach(el => {
            el.textContent = count;
            el.style.display = count > 0 ? '' : 'none';
        });
        // Crée le badge si absent
        if (count > 0) {
            document.querySelectorAll('.btn-cart').forEach(btn => {
                if (!btn.querySelector('.cart-badge')) {
                    const span = document.createElement('span');
                    span.className = 'cart-badge';
                    span.textContent = count;
                    btn.appendChild(span);
                }
            });
        }
    }

    function fillModal(data) {
        const p = data.product;
        const c = data.cart;

        document.getElementById('cartModalName').textContent  = p.name;
        document.getElementById('cartModalPrice').textContent = p.price + ' €';
        document.getElementById('cartModalQty').textContent   = p.quantity > 1
            ? p.quantity + ' exemplaire(s) ajouté(s)'
            : '1 exemplaire ajouté';

        const imgBox = document.getElementById('cartModalImg');
        if (p.image) {
            imgBox.innerHTML = `<img src="${p.image}" style="width:100%;height:100%;object-fit:cover;" alt="">`;
        } else {
            imgBox.innerHTML = '<i class="bi bi-image" style="color:#94a3b8;font-size:1.4rem;"></i>';
        }

        document.getElementById('cartModalCount').textContent       = c.count;
        document.getElementById('cartModalCountPlural').textContent = c.count > 1 ? 's' : '';
        document.getElementById('cartModalTotal').textContent       = c.total + ' €';

        updateBadges(c.count);
    }

    document.addEventListener('submit', function (e) {
        const form = e.target.closest('.js-cart-add');
        if (!form) return;

        e.preventDefault();

        const body = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: body,
        })
        .then(r => r.json())
        .then(data => {
            fillModal(data);
            modal.show();
        })
        .catch(() => {
            // Fallback : soumission normale
            form.submit();
        });
    });
})();
</script>
</body>
</html>
