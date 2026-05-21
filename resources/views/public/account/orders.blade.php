@extends('layouts.public')

@section('title', 'Mes commandes')

@section('content')
<div class="container py-5" style="max-width: 860px;">

    <h1 class="fw-bold mb-4" style="font-size: 1.4rem; color: var(--ap-primary);">Mes commandes</h1>

    @if($orders->isEmpty())
        <div style="background: var(--ap-bg-card); border: 1px solid var(--ap-border); border-radius: var(--ap-radius); padding: 3rem; text-align: center;">
            <i class="bi bi-receipt d-block mb-3" style="font-size: 2.5rem; color: var(--ap-text-muted);"></i>
            <div class="fw-semibold mb-1" style="font-size: .95rem;">Aucune commande pour l'instant.</div>
            <div class="text-muted mb-4" style="font-size: .85rem;">Vos commandes apparaîtront ici une fois passées.</div>
            <a href="{{ route('products.index') }}" class="btn btn-ap-accent">Voir le catalogue</a>
        </div>
    @else
        <div style="background: var(--ap-bg-card); border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden;">
            <table class="table table-hover mb-0" style="font-size: .87rem;">
                <thead style="background: #EDE8DF;">
                    <tr>
                        <th class="px-4 py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">N° commande</th>
                        <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Date</th>
                        <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Total</th>
                        <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Paiement</th>
                        <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Statut</th>
                        <th class="py-3 pe-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr onclick="window.location='{{ route('account.orders.show', $order) }}'" style="cursor: pointer;">
                            <td class="px-4 py-3 font-monospace" style="font-size: .8rem; color: var(--ap-primary);">
                                {{ $order->order_number }}
                            </td>
                            <td class="py-3" style="color: var(--ap-text-muted);">
                                {{ $order->created_at->format('d/m/Y') }}
                            </td>
                            <td class="py-3 fw-semibold">
                                {{ number_format($order->total, 2, ',', ' ') }} €
                            </td>
                            <td class="py-3">
                                @php
                                    $pStyle = match($order->payment_status) {
                                        'paid'    => 'background:#E8F4EC;color:var(--ap-success)',
                                        'failed'  => 'background:#F5E8E8;color:var(--ap-danger)',
                                        'refunded'=> 'background:#f5f3ff;color:#7c3aed',
                                        default   => 'background: var(--ap-bg-card)7ed;color:#c2410c',
                                    };
                                @endphp
                                <span class="ap-badge" style="{{ $pStyle }}">{{ $order->paymentStatusLabel() }}</span>
                            </td>
                            <td class="py-3">
                                @php
                                    $sStyle = match($order->status) {
                                        'delivered' => 'background:#E8F4EC;color:var(--ap-success)',
                                        'cancelled' => 'background:#F5E8E8;color:var(--ap-danger)',
                                        'shipped'   => 'background:#eff6ff;color:#1d4ed8',
                                        default     => 'background:#EDE8DF;color:var(--ap-text-muted)',
                                    };
                                @endphp
                                <span class="ap-badge" style="{{ $sStyle }}">{{ $order->statusLabel() }}</span>
                            </td>
                            <td class="py-3 pe-4 text-end" onclick="event.stopPropagation()">
                                <a href="{{ route('account.orders.show', $order) }}"
                                   style="font-size: .8rem; color: var(--ap-accent); text-decoration: none;">
                                    Détail →
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
        </div>
    @endif

</div>
@endsection
