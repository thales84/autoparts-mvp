@extends('layouts.admin')

@section('title', 'Commande ' . $order->order_number)

@section('content')

<div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
    <a href="{{ route('admin.orders.index') }}" style="color: var(--ap-text-muted); text-decoration: none; font-size: .88rem;">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
    <h1 class="fw-bold mb-0 font-monospace" style="font-size: 1.15rem;">{{ $order->order_number }}</h1>
    <span style="font-size: .75rem; color: var(--ap-text-muted);">{{ $order->created_at->format('d/m/Y à H:i') }}</span>
    <div class="ms-auto d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.orders.pdf.devis', $order) }}" target="_blank"
           class="btn btn-sm" style="background:#f8fafc;border:1px solid var(--ap-border);font-size:.8rem;">
            <i class="bi bi-file-earmark-text me-1"></i>Devis
        </a>
        @if($order->payment_status === 'paid')
            <a href="{{ route('admin.orders.pdf.bon-commande', $order) }}" target="_blank"
               class="btn btn-sm" style="background:#eff6ff;border:1px solid #bfdbfe;font-size:.8rem;color:#1d4ed8;">
                <i class="bi bi-file-earmark-check me-1"></i>Bon de commande
            </a>
        @else
            <span class="btn btn-sm disabled" style="background:#f8fafc;border:1px solid var(--ap-border);font-size:.8rem;color:#94a3b8;cursor:not-allowed;"
                  title="Disponible après validation du paiement">
                <i class="bi bi-file-earmark-check me-1"></i>Bon de commande
            </span>
        @endif
        @if($order->isFullyPaid())
            <a href="{{ route('admin.orders.pdf.facture', $order) }}" target="_blank"
               class="btn btn-sm" style="background:#f0fdf4;border:1px solid #bbf7d0;font-size:.8rem;color:#15803d;">
                <i class="bi bi-receipt me-1"></i>Facture
            </a>
        @elseif($order->proofs->where('status','validated')->isNotEmpty())
            <a href="{{ route('admin.orders.pdf.recu', $order) }}" target="_blank"
               class="btn btn-sm" style="background:#fffbeb;border:1px solid #fde68a;font-size:.8rem;color:#92400e;">
                <i class="bi bi-receipt me-1"></i>Reçu versements
            </a>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" style="font-size: .88rem;" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">

    {{-- Colonne principale --}}
    <div class="col-lg-8">

        {{-- Articles commandés --}}
        <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden; margin-bottom: 1.25rem;">
            <div class="px-4 py-3 fw-bold" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted); border-bottom: 1px solid var(--ap-border);">
                Articles ({{ $order->items->count() }})
            </div>
            <table class="table mb-0" style="font-size: .85rem;">
                <thead style="background: #f8fafc;">
                    <tr>
                        <th class="px-4 py-2 fw-semibold" style="font-size: .75rem; color: var(--ap-text-muted);">Produit</th>
                        <th class="py-2 fw-semibold text-center" style="font-size: .75rem; color: var(--ap-text-muted);">Qté</th>
                        <th class="py-2 fw-semibold text-end" style="font-size: .75rem; color: var(--ap-text-muted);">P.U.</th>
                        <th class="py-2 pe-4 fw-semibold text-end" style="font-size: .75rem; color: var(--ap-text-muted);">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td class="px-4 py-2">
                                <div class="fw-semibold" style="font-size: .87rem;">{{ $item->product_name }}</div>
                                @if($item->product_sku)
                                    <div class="font-monospace" style="font-size: .72rem; color: var(--ap-text-muted);">{{ $item->product_sku }}</div>
                                @endif
                            </td>
                            <td class="py-2 text-center">{{ $item->quantity }}</td>
                            <td class="py-2 text-end">{{ number_format($item->unit_price, 0, ',', ' ') }}</td>
                            <td class="py-2 pe-4 text-end fw-semibold">{{ number_format($item->line_total, 0, ',', ' ') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot style="border-top: 2px solid var(--ap-border);">
                    <tr>
                        <td colspan="3" class="px-4 py-2 fw-bold text-end">Total</td>
                        <td class="py-2 pe-4 fw-bold text-end" style="font-size: 1rem; color: var(--ap-accent);">
                            {{ number_format($order->total, 2, ',', ' ') }} €
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Informations client --}}
        <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); padding: 1.5rem;">
            <div class="fw-bold mb-3" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">Client</div>
            <div class="row g-3">
                <div class="col-sm-6">
                    <div style="font-size: .75rem; color: var(--ap-text-muted); margin-bottom: .2rem;">Nom</div>
                    <div class="fw-semibold" style="font-size: .87rem;">{{ $order->customer_name }}</div>
                </div>
                <div class="col-sm-6">
                    <div style="font-size: .75rem; color: var(--ap-text-muted); margin-bottom: .2rem;">Email</div>
                    <div style="font-size: .87rem;">{{ $order->customer_email }}</div>
                </div>
                @if($order->customer_phone)
                    <div class="col-sm-6">
                        <div style="font-size: .75rem; color: var(--ap-text-muted); margin-bottom: .2rem;">Téléphone</div>
                        <div style="font-size: .87rem;">{{ $order->customer_phone }}</div>
                    </div>
                @endif
                @if($order->delivery_address)
                    <div class="col-12">
                        <div style="font-size: .75rem; color: var(--ap-text-muted); margin-bottom: .2rem;">Adresse de livraison</div>
                        <div style="font-size: .87rem;">{{ $order->delivery_address }}</div>
                    </div>
                @endif
                @if($order->notes)
                    <div class="col-12">
                        <div style="font-size: .75rem; color: var(--ap-text-muted); margin-bottom: .2rem;">Notes client</div>
                        <div style="font-size: .87rem; background: #f8fafc; padding: .75rem; border-radius: 6px;">{{ $order->notes }}</div>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Colonne latérale --}}
    <div class="col-lg-4">

        {{-- Statuts actuels --}}
        <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); padding: 1.5rem; margin-bottom: 1.25rem;">
            <div class="fw-bold mb-3" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">Statuts actuels</div>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex justify-content-between align-items-center">
                    <span style="font-size: .82rem; color: var(--ap-text-muted);">Commande</span>
                    @php
                        $sStyle = match($order->status) {
                            'delivered' => 'background:#f0fdf4;color:var(--ap-success)',
                            'cancelled' => 'background:#fef2f2;color:var(--ap-danger)',
                            'shipped'   => 'background:#eff6ff;color:#1d4ed8',
                            default     => 'background:#f8fafc;color:var(--ap-text-muted)',
                        };
                    @endphp
                    <span class="ap-badge" style="{{ $sStyle }}">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span style="font-size: .82rem; color: var(--ap-text-muted);">Paiement</span>
                    @php
                        $pStyle = match($order->payment_status) {
                            'paid'    => 'background:#f0fdf4;color:var(--ap-success)',
                            'failed'  => 'background:#fef2f2;color:var(--ap-danger)',
                            'refunded'=> 'background:#f5f3ff;color:#7c3aed',
                            default   => 'background:#fff7ed;color:#c2410c',
                        };
                    @endphp
                    <span class="ap-badge" style="{{ $pStyle }}">{{ $order->paymentStatusLabel() }}</span>
                </div>
            </div>
        </div>

        {{-- Modifier le statut --}}
        <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); padding: 1.5rem; margin-bottom: 1.25rem;">
            <div class="fw-bold mb-3" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">Modifier le statut</div>

            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size: .82rem;">Statut commande</label>
                    <select name="status" class="form-select form-select-sm">
                        @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size: .82rem;">Statut paiement</label>
                    <select name="payment_status" class="form-select form-select-sm">
                        @foreach(['unpaid','pending','paid','failed','refunded'] as $s)
                            <option value="{{ $s }}" {{ $order->payment_status === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-ap-accent btn-sm w-100">
                    <i class="bi bi-check-lg me-1"></i>Mettre à jour
                </button>
            </form>
        </div>

        {{-- Paiements enregistrés --}}
        @if($order->payments->isNotEmpty())
            <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); padding: 1.5rem;">
                <div class="fw-bold mb-3" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">Paiements</div>
                @foreach($order->payments as $payment)
                    <div style="font-size: .82rem; padding: .5rem 0; {{ ! $loop->last ? 'border-bottom: 1px solid var(--ap-border);' : '' }}">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">{{ number_format($payment->amount, 2, ',', ' ') }} €</span>
                            <span class="ap-badge ap-badge-stock">{{ $payment->status }}</span>
                        </div>
                        <div style="color: var(--ap-text-muted); margin-top: .2rem;">{{ $payment->provider }} · {{ $payment->created_at->format('d/m/Y H:i') }}</div>
                        @if($payment->transaction_id)
                            <div class="font-monospace" style="font-size: .7rem; color: var(--ap-text-muted);">{{ $payment->transaction_id }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>

@endsection
