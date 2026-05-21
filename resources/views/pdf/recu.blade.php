<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #1e293b; background: #fff; }
    .header { background: #0f1f3d; color: #fff; padding: 28px 36px; display: flex; justify-content: space-between; align-items: flex-start; }
    .brand { font-size: 20px; font-weight: 700; letter-spacing: .5px; }
    .brand-sub { font-size: 11px; color: rgba(255,255,255,.7); margin-top: 4px; }
    .doc-type { text-align: right; }
    .doc-type .label { font-size: 11px; color: rgba(255,255,255,.6); text-transform: uppercase; letter-spacing: 1px; }
    .doc-type .number { font-size: 18px; font-weight: 700; color: #22c55e; font-family: monospace; }
    .doc-type .date { font-size: 11px; color: rgba(255,255,255,.7); margin-top: 3px; }
    .paid-stamp { background: #22c55e; color: #fff; text-align: center; padding: 10px; font-size: 15px; font-weight: 700; letter-spacing: 3px; text-transform: uppercase; }
    .body { padding: 28px 36px; }
    .parties { display: flex; gap: 0; margin-bottom: 24px; }
    .party { flex: 1; }
    .party-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 6px; }
    .party-name { font-size: 14px; font-weight: 700; color: #0f1f3d; }
    .party-info { font-size: 12px; color: #475569; line-height: 1.6; margin-top: 3px; }
    .section-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 8px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    thead th { background: #f8fafc; padding: 8px 12px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #64748b; border-bottom: 2px solid #e2e8f0; }
    tbody td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
    tbody tr:last-child td { border-bottom: none; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .total-row td { background: #0f1f3d; color: #fff; font-weight: 700; font-size: 14px; padding: 10px 12px; }
    .divider { height: 1px; background: #e2e8f0; margin: 20px 0; }
    .payments-list { background: #f0fdf4; border-radius: 8px; padding: 14px 16px; margin-bottom: 20px; border: 1px solid #bbf7d0; }
    .payment-row { display: flex; justify-content: space-between; padding: 5px 0; font-size: 13px; border-bottom: 1px solid #dcfce7; }
    .payment-row:last-child { border-bottom: none; }
    .footer-pdf { border-top: 2px solid #e2e8f0; padding-top: 14px; font-size: 11px; color: #94a3b8; text-align: center; }
    .legal-line { font-size: 9px; color: #cbd5e1; text-align: center; margin-top: 6px; }
</style>
</head>
<body>

@php $currency = $settings['doc_currency'] ?? '€'; @endphp

<div class="header">
    <div>
        <div class="brand">{{ $settings['company_name'] ?? '' }}</div>
        @if(!empty($settings['company_tagline']))<div class="brand-sub">{{ $settings['company_tagline'] }}</div>@endif
        @if(!empty($settings['company_address']))<div class="brand-sub">{{ $settings['company_address'] }}</div>@endif
        @if(!empty($settings['company_phone']))<div class="brand-sub">{{ $settings['company_phone'] }}</div>@endif
    </div>
    <div class="doc-type">
        <div class="label">Reçu de paiement</div>
        <div class="number">{{ $order->order_number }}</div>
        <div class="date">{{ now()->format('d/m/Y') }}</div>
    </div>
</div>

<div class="paid-stamp">✓ Paiement reçu et validé</div>

<div class="body">

    <div class="parties">
        <div class="party">
            <div class="party-label">Émis par</div>
            <div class="party-name">{{ $settings['company_name'] ?? '' }}</div>
            @if(!empty($settings['contact_email']))<div class="party-info">{{ $settings['contact_email'] }}</div>@endif
            @if(!empty($settings['company_phone']))<div class="party-info">{{ $settings['company_phone'] }}</div>@endif
        </div>
        <div class="party" style="text-align: right;">
            <div class="party-label">Client</div>
            <div class="party-name">{{ $order->customer_name }}</div>
            <div class="party-info">{{ $order->customer_email }}</div>
            @if($order->customer_phone)<div class="party-info">{{ $order->customer_phone }}</div>@endif
        </div>
    </div>

    <div class="divider"></div>

    <div class="section-title">Articles</div>
    <table>
        <thead>
            <tr>
                <th>Désignation</th>
                <th class="text-center">Qté</th>
                <th class="text-right">Prix unitaire</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->product_sku)<br><span style="font-size:11px;color:#94a3b8;">Réf. {{ $item->product_sku }}</span>@endif
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2, ',', ' ') }} {{ $currency }}</td>
                    <td class="text-right">{{ number_format($item->line_total, 2, ',', ' ') }} {{ $currency }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right">TOTAL</td>
                <td class="text-right">{{ number_format($order->total, 2, ',', ' ') }} {{ $currency }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Versements validés --}}
    @php $validatedProofs = $order->proofs->where('status', 'validated'); @endphp
    @if($validatedProofs->count() > 1)
        <div class="section-title">Détail des versements</div>
        <div class="payments-list">
            @foreach($validatedProofs as $proof)
                <div class="payment-row">
                    <span style="color: #475569;">Versement du {{ $proof->reviewed_at?->format('d/m/Y') ?? $proof->created_at->format('d/m/Y') }}</span>
                    <span style="font-weight: 600; color: #16a34a;">{{ number_format($proof->amount, 2, ',', ' ') }} {{ $currency }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <div style="background: #f0fdf4; border: 2px solid #22c55e; border-radius: 8px; padding: 16px; text-align: center; margin-bottom: 24px;">
        <div style="font-size: 11px; color: #16a34a; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; margin-bottom: 4px;">Montant total réglé</div>
        <div style="font-size: 24px; font-weight: 700; color: #15803d;">{{ number_format($order->total, 2, ',', ' ') }} {{ $currency }}</div>
    </div>

    <div class="footer-pdf">
        Reçu émis le {{ now()->format('d/m/Y à H:i') }} — {{ $settings['company_name'] ?? '' }}<br>
        Ce document atteste du paiement complet de la commande {{ $order->order_number }}.
    </div>
    @php
        $legal = [];
        if (!empty($settings['company_siret']))      $legal[] = 'SIRET : ' . $settings['company_siret'];
        if (!empty($settings['company_rcs']))        $legal[] = $settings['company_rcs'];
        if (!empty($settings['company_legal_form'])) $legal[] = $settings['company_legal_form'];
        if (!empty($settings['company_vat']))        $legal[] = 'TVA : ' . $settings['company_vat'];
    @endphp
    @if(count($legal))
    <div class="legal-line">{{ implode(' — ', $legal) }}</div>
    @endif

</div>
</body>
</html>
