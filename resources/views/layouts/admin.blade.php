<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Admin | {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    @stack('styles')
</head>
<body>

{{-- ======== ADMIN NAVBAR ======== --}}
<nav class="navbar ap-admin-navbar navbar-expand-lg">
    <div class="container-fluid px-3">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2 brand-icon me-1"></i>PALERME AUTO PRO — Admin
        </a>

        <button class="navbar-toggler border-0" type="button"
                data-bs-toggle="collapse" data-bs-target="#adminNav"
                style="filter: invert(1);">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}" target="_blank">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Voir le site
                    </a>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-link text-danger border-0 bg-transparent">
                            <i class="bi bi-box-arrow-right me-1"></i>Déconnexion
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- ======== LAYOUT SIDEBAR + MAIN ======== --}}
<div class="ap-admin-layout">

    {{-- Sidebar --}}
    <aside class="ap-admin-sidebar d-none d-lg-block">
        <div class="sidebar-section">Principal</div>

        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid-1x2-fill"></i>Dashboard
            </a>
        </nav>

        <div class="sidebar-section">Catalogue</div>

        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
               href="{{ route('admin.products.index') }}">
                <i class="bi bi-box-seam-fill"></i>Produits
            </a>
            <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
               href="{{ route('admin.categories.index') }}">
                <i class="bi bi-tags-fill"></i>Catégories
            </a>
        </nav>

        <div class="sidebar-section">Commerce</div>

        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
               href="{{ route('admin.orders.index') }}">
                <i class="bi bi-receipt-cutoff"></i>Commandes
            </a>
            <a class="nav-link {{ request()->routeIs('admin.payment-proofs.*') ? 'active' : '' }}"
               href="{{ route('admin.payment-proofs.index') }}"
               style="position: relative;">
                <i class="bi bi-shield-check"></i>Preuves paiement
                @php $pendingCount = \App\Models\PaymentProof::where('status','pending')->count(); @endphp
                @if($pendingCount)
                    <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: #f87171; color: #fff; border-radius: 99px; font-size: .65rem; font-weight: 700; padding: 1px 6px; line-height: 1.4;">{{ $pendingCount }}</span>
                @endif
            </a>
            <a class="nav-link {{ request()->routeIs('admin.part-requests.*') ? 'active' : '' }}"
               href="{{ route('admin.part-requests.index') }}">
                <i class="bi bi-search-heart"></i>Demandes pièces
            </a>
        </nav>

        <div class="sidebar-section">Configuration</div>

        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('admin.payment-settings.*') ? 'active' : '' }}"
               href="{{ route('admin.payment-settings.edit') }}">
                <i class="bi bi-gear-fill"></i>Paramètres
            </a>
        </nav>

        <div class="sidebar-section">Compte</div>

        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}"
               href="{{ route('admin.profile.edit') }}">
                <i class="bi bi-person-circle"></i>Mon profil
            </a>
            <a class="nav-link" href="{{ route('home') }}" target="_blank">
                <i class="bi bi-house-door"></i>Voir le site
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent"
                        style="color: #f87171 !important;">
                    <i class="bi bi-box-arrow-right"></i>Déconnexion
                </button>
            </form>
        </nav>
    </aside>

    {{-- Main content --}}
    <main class="ap-admin-main">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="ap-flash ap-flash-success alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="ap-flash ap-flash-error alert-dismissible fade show mb-3" role="alert">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Page header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 fw-bold" style="color: var(--ap-text);">
                @yield('title', 'Dashboard')
            </h4>
            @hasSection('page-actions')
                <div class="d-flex gap-2">
                    @yield('page-actions')
                </div>
            @endif
        </div>

        @yield('content')

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
