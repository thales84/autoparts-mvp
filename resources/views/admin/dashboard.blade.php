@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="ap-stat-card">
            <div class="stat-icon-wrap" style="background: #EEF2F8; color: var(--ap-primary);">
                <i class="bi bi-box-seam-fill"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['products_active'] }}</div>
                <div class="stat-label">Produits actifs</div>
                @if($stats['products_inactive'])
                    <div style="font-size: .72rem; color: var(--ap-text-muted);">{{ $stats['products_inactive'] }} inactif(s)</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="ap-stat-card">
            <div class="stat-icon-wrap" style="background: #fff7ed; color: var(--ap-accent);">
                <i class="bi bi-receipt-cutoff"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['orders_total'] }}</div>
                <div class="stat-label">Commandes</div>
                @if($stats['orders_unpaid'])
                    <div style="font-size: .72rem; color: var(--ap-danger);">{{ $stats['orders_unpaid'] }} non payée(s)</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="ap-stat-card">
            <div class="stat-icon-wrap" style="background: #f0fdf4; color: var(--ap-success);">
                <i class="bi bi-cash-coin"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['orders_paid'] }}</div>
                <div class="stat-label">Commandes payées</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="ap-stat-card">
            <div class="stat-icon-wrap" style="background: #fef2f2; color: var(--ap-danger);">
                <i class="bi bi-search-heart"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['requests_new'] }}</div>
                <div class="stat-label">Nouvelles demandes</div>
                @if($stats['requests_total'])
                    <div style="font-size: .72rem; color: var(--ap-text-muted);">{{ $stats['requests_total'] }} au total</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="ap-stat-card">
            <div class="stat-icon-wrap" style="background: #f0f9ff; color: #0369a1;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['customers_total'] }}</div>
                <div class="stat-label">Clients inscrits</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- Dernières commandes --}}
    <div class="col-lg-7">
        <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden;">
            <div class="px-4 py-3 d-flex justify-content-between align-items-center"
                 style="border-bottom: 1px solid var(--ap-border);">
                <span class="fw-bold" style="font-size: .88rem;">Dernières commandes</span>
                <a href="{{ route('admin.orders.index') }}"
                   style="font-size: .8rem; color: var(--ap-accent); text-decoration: none;">Tout voir →</a>
            </div>
            @if($recentOrders->isEmpty())
                <div class="p-4 text-center text-muted" style="font-size: .88rem;">Aucune commande.</div>
            @else
                <table class="table table-hover mb-0" style="font-size: .85rem;">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th class="px-4 py-2 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">N°</th>
                            <th class="py-2 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Client</th>
                            <th class="py-2 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Total</th>
                            <th class="py-2 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr onclick="window.location='{{ route('admin.orders.show', $order) }}'" style="cursor: pointer;">
                                <td class="px-4 py-2 font-monospace" style="font-size: .8rem;">{{ $order->order_number }}</td>
                                <td class="py-2">{{ $order->customer_name }}</td>
                                <td class="py-2 fw-semibold">{{ number_format($order->total, 0, ',', ' ') }}</td>
                                <td class="py-2">
                                    @if($order->payment_status === 'paid')
                                        <span class="ap-badge ap-badge-stock">Payée</span>
                                    @else
                                        <span class="ap-badge ap-badge-oos">{{ $order->paymentStatusLabel() }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- Nouvelles demandes + Actions rapides --}}
    <div class="col-lg-5 d-flex flex-column gap-3">

        {{-- Actions rapides --}}
        <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); padding: 1.2rem;">
            <div class="fw-bold mb-3" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">Actions rapides</div>
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.products.create') }}" class="btn btn-ap-accent btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>Nouveau produit
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-ap-outline-primary btn-sm">
                    <i class="bi bi-box-seam me-1"></i>Gérer les produits
                </a>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-ap-outline-primary btn-sm">
                    <i class="bi bi-receipt me-1"></i>Toutes les commandes
                </a>
                <a href="{{ route('admin.part-requests.index') }}" class="btn btn-ap-outline-primary btn-sm">
                    <i class="bi bi-search-heart me-1"></i>Demandes de pièces
                </a>
            </div>
        </div>

        {{-- Nouvelles demandes --}}
        <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden; flex: 1;">
            <div class="px-4 py-3 d-flex justify-content-between align-items-center"
                 style="border-bottom: 1px solid var(--ap-border);">
                <span class="fw-bold" style="font-size: .88rem;">Demandes récentes</span>
                <a href="{{ route('admin.part-requests.index') }}"
                   style="font-size: .8rem; color: var(--ap-accent); text-decoration: none;">Tout voir →</a>
            </div>
            @if($recentRequests->isEmpty())
                <div class="p-4 text-center text-muted" style="font-size: .88rem;">Aucune nouvelle demande.</div>
            @else
                <div>
                    @foreach($recentRequests as $req)
                        <a href="{{ route('admin.part-requests.show', $req) }}"
                           class="d-flex gap-3 align-items-center px-4 py-2 text-decoration-none"
                           style="{{ ! $loop->last ? 'border-bottom: 1px solid var(--ap-border);' : '' }} color: var(--ap-text);">
                            <div style="width: 32px; height: 32px; background: #fff7ed; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .9rem; color: var(--ap-accent); flex-shrink: 0;">
                                <i class="bi bi-search-heart"></i>
                            </div>
                            <div style="min-width: 0; flex: 1;">
                                <div class="fw-semibold text-truncate" style="font-size: .85rem;">{{ $req->requested_part_name }}</div>
                                <div class="text-muted" style="font-size: .75rem;">{{ $req->contact_name }}</div>
                            </div>
                            <span class="ap-badge" style="background: #dbeafe; color: #1e40af; flex-shrink: 0;">Nouvelle</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

{{-- Derniers clients inscrits --}}
<div class="row g-4 mt-0">
    <div class="col-12">
        <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden;">
            <div class="px-4 py-3" style="border-bottom: 1px solid var(--ap-border);">
                <span class="fw-bold" style="font-size: .88rem;">Derniers clients inscrits</span>
            </div>
            @if($recentUsers->isEmpty())
                <div class="p-4 text-center text-muted" style="font-size: .88rem;">Aucun client inscrit.</div>
            @else
                <table class="table table-hover mb-0" style="font-size: .85rem;">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th class="px-4 py-2 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Nom</th>
                            <th class="py-2 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">E-mail</th>
                            <th class="py-2 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Téléphone</th>
                            <th class="py-2 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Inscrit le</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsers as $customer)
                            <tr>
                                <td class="px-4 py-2 fw-semibold">{{ $customer->name }}</td>
                                <td class="py-2" style="color: var(--ap-text-muted);">{{ $customer->email }}</td>
                                <td class="py-2" style="color: var(--ap-text-muted);">{{ $customer->phone ?? '—' }}</td>
                                <td class="py-2" style="color: var(--ap-text-muted); font-size: .8rem;">{{ $customer->created_at->format('d/m/Y à H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

@endsection
