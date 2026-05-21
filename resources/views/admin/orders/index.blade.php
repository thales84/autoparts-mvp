@extends('layouts.admin')

@section('title', 'Commandes')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold" style="font-size: 1.25rem;">Commandes</h1>
    <div style="font-size: .82rem; color: var(--ap-text-muted);">{{ $orders->total() }} commande(s) au total</div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" style="font-size: .88rem;" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden;">
    @if($orders->isEmpty())
        <div class="p-5 text-center text-muted" style="font-size: .88rem;">Aucune commande.</div>
    @else
        <table class="table table-hover mb-0" style="font-size: .85rem;">
            <thead style="background: #f8fafc;">
                <tr>
                    <th class="px-4 py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">N° commande</th>
                    <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Client</th>
                    <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Total</th>
                    <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Paiement</th>
                    <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Statut</th>
                    <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Date</th>
                    <th class="py-3 pe-4 fw-semibold text-end" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr onclick="window.location='{{ route('admin.orders.show', $order) }}'" style="cursor: pointer;">
                        <td class="px-4 py-2 font-monospace" style="font-size: .8rem; color: var(--ap-primary);">
                            {{ $order->order_number }}
                        </td>
                        <td class="py-2">
                            <div class="fw-semibold" style="font-size: .87rem;">{{ $order->customer_name }}</div>
                            <div style="font-size: .72rem; color: var(--ap-text-muted);">{{ $order->customer_email }}</div>
                        </td>
                        <td class="py-2 fw-semibold">
                            {{ number_format($order->total, 0, ',', ' ') }}
                        </td>
                        <td class="py-2">
                            @php
                                $pStyle = match($order->payment_status) {
                                    'paid'    => 'background:#f0fdf4;color:var(--ap-success)',
                                    'failed'  => 'background:#fef2f2;color:var(--ap-danger)',
                                    'refunded'=> 'background:#f5f3ff;color:#7c3aed',
                                    default   => 'background:#fff7ed;color:#c2410c',
                                };
                            @endphp
                            <span class="ap-badge" style="{{ $pStyle }}">{{ $order->paymentStatusLabel() }}</span>
                        </td>
                        <td class="py-2">
                            @php
                                $sStyle = match($order->status) {
                                    'delivered' => 'background:#f0fdf4;color:var(--ap-success)',
                                    'cancelled' => 'background:#fef2f2;color:var(--ap-danger)',
                                    'shipped'   => 'background:#eff6ff;color:#1d4ed8',
                                    default     => 'background:#f8fafc;color:var(--ap-text-muted)',
                                };
                            @endphp
                            <span class="ap-badge" style="{{ $sStyle }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td class="py-2" style="color: var(--ap-text-muted); font-size: .8rem;">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="py-2 pe-4 text-end" onclick="event.stopPropagation()">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="btn btn-ap-outline-primary btn-sm"
                               style="padding: .25rem .65rem; font-size: .8rem;">
                                Voir
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($orders->hasPages())
            <div class="px-4 py-3" style="border-top: 1px solid var(--ap-border);">
                {{ $orders->links() }}
            </div>
        @endif
    @endif
</div>

@endsection
