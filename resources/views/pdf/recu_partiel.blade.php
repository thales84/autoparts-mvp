<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: DejaVu Sans, sans-serif; font-size:11px; color:#1e293b; background:#fff; }

@page { margin: 0; }

/* ── Footer fixé ── */
.pdf-footer {
    position: fixed;
    bottom: 0;
    left: 0; right: 0;
    height: 30mm;
    background: #fff;
    border-top: 2px solid #e2e8f0;
    padding: 7px 28px 0;
}
.footer-top-table { width:100%; border-collapse:collapse; margin-bottom:5px; }
.footer-iban { font-size:9px; font-family:monospace; color:#334155; }
.footer-pagenum { font-size:9px; color:#94a3b8; text-align:right; }
.footer-pagenum::after { content: "Page " counter(page) " / " counter(pages); }
.footer-legal { font-size:8.5px; color:#94a3b8; text-align:center; line-height:1.55; border-top:1px solid #f1f5f9; padding-top:5px; }
.footer-date { font-size:8px; color:#cbd5e1; text-align:center; margin-top:3px; }

/* ── Header ── */
.header-table { width:100%; border-collapse:collapse; background:#0f172a; }
.header-table td { padding:22px 28px; vertical-align:top; }
.header-logo-img { max-height:55px; max-width:180px; }
.header-brand { font-size:17px; font-weight:700; color:#fff; letter-spacing:.2px; }
.header-tagline { font-size:9.5px; color:rgba(255,255,255,.6); margin-top:3px; }
.header-addr { font-size:9.5px; color:rgba(255,255,255,.6); margin-top:2px; }
.header-doc-label { font-size:9px; color:rgba(255,255,255,.5); text-transform:uppercase; letter-spacing:1.8px; text-align:right; }
.header-doc-number { font-size:19px; font-weight:700; color:#fbbf24; font-family:monospace; text-align:right; margin-top:4px; }
.header-doc-date { font-size:9.5px; color:rgba(255,255,255,.6); text-align:right; margin-top:3px; }

/* ── Accent stripe + banner ── */
.accent-stripe { height:3px; background:#d97706; }
.status-banner { background:#d97706; color:#fff; text-align:center; padding:9px; font-size:11px; font-weight:700; letter-spacing:2px; text-transform:uppercase; }

/* ── Body ── */
.pdf-body { padding:18px 28px 35mm; }

/* ── Section label ── */
.section-label {
    font-size:8.5px; font-weight:700; text-transform:uppercase; letter-spacing:1.5px;
    color:#94a3b8; border-bottom:1px solid #e2e8f0; padding-bottom:5px; margin-bottom:12px;
}

/* ── Parties ── */
.parties-table { width:100%; border-collapse:collapse; margin-bottom:18px; }
.party-box { background:#f8fafc; border:1px solid #e2e8f0; border-radius:5px; padding:12px 14px; }
.party-tag { font-size:8px; font-weight:700; text-transform:uppercase; letter-spacing:1.2px; color:#94a3b8; margin-bottom:5px; }
.party-name { font-size:13px; font-weight:700; color:#0f172a; margin-bottom:3px; }
.party-info { font-size:10px; color:#475569; line-height:1.65; }

/* ── Articles ── */
.articles-table { width:100%; border-collapse:collapse; font-size:11px; }
.articles-table thead th {
    background:#f1f5f9; padding:8px 10px;
    font-size:8.5px; font-weight:700; text-transform:uppercase; letter-spacing:.8px;
    color:#64748b; border-bottom:2px solid #e2e8f0;
}
.articles-table tbody td { padding:9px 10px; border-bottom:1px solid #f1f5f9; vertical-align:top; }
.articles-table tbody tr:last-child td { border-bottom:1px solid #e2e8f0; }
.sku { font-size:9px; color:#94a3b8; font-family:monospace; margin-top:2px; }
.text-right { text-align:right; }
.text-center { text-align:center; }

/* ── Résumé commande ── */
.summary-outer { width:100%; border-collapse:collapse; margin-top:0; margin-bottom:4px; }
.summary-box { border:1px solid #e2e8f0; border-radius:5px; overflow:hidden; }
.summary-row-table { width:100%; border-collapse:collapse; }
.sum-row td { padding:7px 12px; font-size:11px; border-bottom:1px solid #f1f5f9; }
.sum-row-total { background:#0f172a; }
.sum-row-total td { padding:10px 12px; font-size:13px; font-weight:700; color:#fff; border-bottom:none; }
.vat-note { font-size:8px; color:#94a3b8; text-align:right; font-style:italic; margin-bottom:14px; }

/* ── Versements ── */
.versements-box { background:#fefce8; border:1px solid #fde68a; border-radius:5px; padding:12px 14px; margin-bottom:18px; }
.versement-row-table { width:100%; border-collapse:collapse; }
.versement-row td { padding:6px 0; font-size:11px; border-bottom:1px solid #fde68a; }
.versement-row:last-child td { border-bottom:none; }
.versement-amount { color:#92400e; font-weight:700; text-align:right; }

/* ── Bilan paiement ── */
.bilan-table-outer { width:100%; border-collapse:collapse; margin-bottom:20px; }
.bilan-box { border-radius:6px; overflow:hidden; border:1px solid #e2e8f0; }
.bilan-row-table { width:100%; border-collapse:collapse; }
.bilan-paid td { background:#f0fdf4; padding:10px 14px; border-bottom:1px solid #dcfce7; font-size:12px; }
.bilan-paid-amount { color:#16a34a; font-weight:700; font-size:14px; text-align:right; }
.bilan-remaining td { background:#fff7ed; padding:12px 14px; border-bottom:none; font-size:13px; font-weight:700; }
.bilan-remaining-amount { color:#b45309; font-size:16px; text-align:right; }

/* ── Instructions next payment ── */
.next-payment-box { border:1.5px solid #d97706; border-radius:5px; background:#fffbeb; padding:14px; margin-bottom:18px; }
.np-title { font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#92400e; margin-bottom:8px; }
.pm-method { padding-bottom:8px; margin-bottom:8px; border-bottom:1px solid #fde68a; }
.pm-method:last-child { padding-bottom:0; margin-bottom:0; border-bottom:none; }
.pm-label { font-size:8.5px; font-weight:700; text-transform:uppercase; letter-spacing:.8px; color:#92400e; margin-bottom:3px; }
.pm-value { font-size:11px; color:#1e293b; font-family:monospace; margin-bottom:1px; }

/* ── Note ── */
.note-box { border-left:3px solid #d97706; background:#fffbeb; padding:10px 14px; margin-bottom:16px; font-size:10.5px; color:#78350f; line-height:1.6; }
</style>
</head>
<body>

{{-- ══════════ FOOTER FIXÉ ══════════ --}}
<div class="pdf-footer">
    <table class="footer-top-table">
        <tr>
            <td class="footer-iban">
                @if(!empty($settings['payment_bank_iban']))
                    @if(!empty($settings['payment_bank_name'])){{ $settings['payment_bank_name'] }} — @endif
                    @if(!empty($settings['payment_bank_holder'])){{ $settings['payment_bank_holder'] }} — @endif
                    IBAN : {{ $settings['payment_bank_iban'] }}
                    @if(!empty($settings['payment_bank_bic'])) · BIC : {{ $settings['payment_bank_bic'] }}@endif
                @endif
            </td>
            <td class="footer-pagenum"></td>
        </tr>
    </table>
    <div class="footer-legal">
        @php
            $legal = [];
            if (!empty($settings['company_name']))        $legal[] = $settings['company_name'];
            if (!empty($settings['company_legal_form']))  $legal[] = $settings['company_legal_form'];
            if (!empty($settings['company_capital']))     $legal[] = 'Capital : ' . $settings['company_capital'] . ' €';
            if (!empty($settings['company_siret']))       $legal[] = 'SIRET : ' . $settings['company_siret'];
            if (!empty($settings['company_rcs']))         $legal[] = $settings['company_rcs'];
            if (!empty($settings['company_ape']))         $legal[] = 'APE : ' . $settings['company_ape'];
            if (!empty($settings['company_vat']))         $legal[] = 'TVA : ' . $settings['company_vat'];
        @endphp
        @if(count($legal)) {{ implode(' — ', $legal) }}<br>@endif
        @if(!empty($settings['company_footer_legal'])){{ $settings['company_footer_legal'] }}<br>@endif
        <span class="footer-date">Document généré le {{ now()->format('d/m/Y à H:i') }} · Reçu provisoire — la facture définitive sera émise après règlement intégral</span>
    </div>
</div>

{{-- ══════════ HEADER ══════════ --}}
<table class="header-table">
    <tr>
        <td style="width:55%;">
            @if(!empty($logoBase64))
                <img src="{{ $logoBase64 }}" class="header-logo-img">
            @else
                <div class="header-brand">{{ $settings['company_name'] ?? '' }}</div>
                @if(!empty($settings['company_tagline']))<div class="header-tagline">{{ $settings['company_tagline'] }}</div>@endif
            @endif
            @if(!empty($settings['company_address']))<div class="header-addr">{{ $settings['company_address'] }}</div>@endif
            @if(!empty($settings['company_phone']))<div class="header-addr">{{ $settings['company_phone'] }}</div>@endif
            @if(!empty($settings['contact_email']))<div class="header-addr">{{ $settings['contact_email'] }}</div>@endif
        </td>
        <td style="width:45%;text-align:right;">
            <div class="header-doc-label">Reçu de versement(s)</div>
            <div class="header-doc-number">{{ $docNumber ?? $order->order_number }}</div>
            <div class="header-doc-date">Émis le {{ now()->format('d/m/Y') }}</div>
            <div style="font-size:9px;color:rgba(255,255,255,.45);text-align:right;margin-top:2px;">
                Commande du {{ $order->created_at->format('d/m/Y') }}
            </div>
        </td>
    </tr>
</table>
<div class="accent-stripe"></div>
<div class="status-banner">⏳ Paiement en cours — Solde restant à régler</div>

{{-- ══════════ BODY ══════════ --}}
<div class="pdf-body">

@php
    $vatRegime  = $settings['vat_regime'] ?? 'marge';
    $vatRate    = (float)($settings['vat_rate'] ?? 20);
    $currency   = $settings['doc_currency'] ?? '€';
    $totalHT    = null;
    $totalTVA   = null;
    if ($vatRegime === 'standard') {
        $totalHT  = $order->total / (1 + $vatRate / 100);
        $totalTVA = $order->total - $totalHT;
    }
@endphp

    {{-- Parties --}}
    <table class="parties-table">
        <tr>
            <td style="width:48%;padding-right:8px;vertical-align:top;">
                <div class="party-box">
                    <div class="party-tag">Vendeur</div>
                    <div class="party-name">{{ $settings['company_name'] ?? '' }}</div>
                    <div class="party-info">
                        @if(!empty($settings['company_address'])){{ $settings['company_address'] }}<br>@endif
                        @if(!empty($settings['company_phone'])){{ $settings['company_phone'] }}<br>@endif
                        @if(!empty($settings['contact_email'])){{ $settings['contact_email'] }}@endif
                    </div>
                </div>
            </td>
            <td style="width:4%;"></td>
            <td style="width:48%;padding-left:8px;vertical-align:top;">
                <div class="party-box">
                    <div class="party-tag">Client</div>
                    <div class="party-name">{{ $order->customer_name }}</div>
                    <div class="party-info">
                        {{ $order->customer_email }}<br>
                        @if($order->customer_phone){{ $order->customer_phone }}<br>@endif
                        @if($order->delivery_address){{ $order->delivery_address }}@endif
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Articles --}}
    <div class="section-label">Désignation des articles</div>
    <table class="articles-table">
        <colgroup>
            <col style="width:50%">
            <col style="width:8%">
            <col style="width:21%">
            <col style="width:21%">
        </colgroup>
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
                    @if($item->product_sku)<div class="sku">Réf. {{ $item->product_sku }}</div>@endif
                </td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->unit_price, 2, ',', ' ') }} {{ $currency }}</td>
                <td class="text-right"><strong>{{ number_format($item->line_total, 2, ',', ' ') }} {{ $currency }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Résumé commande --}}
    <table class="summary-outer">
        <tr>
            <td style="width:55%;"></td>
            <td style="width:45%;vertical-align:top;padding-left:12px;">
                <div class="summary-box">
                    <table class="summary-row-table">
                        @if($order->delivery_fee > 0)
                        <tr class="sum-row">
                            <td style="color:#64748b;">Sous-total</td>
                            <td class="text-right">{{ number_format($order->subtotal, 2, ',', ' ') }} {{ $currency }}</td>
                        </tr>
                        <tr class="sum-row">
                            <td style="color:#64748b;">Livraison</td>
                            <td class="text-right">{{ number_format($order->delivery_fee, 2, ',', ' ') }} {{ $currency }}</td>
                        </tr>
                        @endif
                        @if($vatRegime === 'standard' && $totalHT !== null)
                        <tr class="sum-row">
                            <td style="color:#64748b;">Total HT</td>
                            <td class="text-right">{{ number_format($totalHT, 2, ',', ' ') }} {{ $currency }}</td>
                        </tr>
                        <tr class="sum-row">
                            <td style="color:#64748b;">TVA {{ number_format($vatRate, 1) }} %</td>
                            <td class="text-right">{{ number_format($totalTVA, 2, ',', ' ') }} {{ $currency }}</td>
                        </tr>
                        @endif
                        <tr class="sum-row-total">
                            <td>MONTANT TOTAL DÛ</td>
                            <td class="text-right" style="color:#fbbf24;">{{ number_format($order->total, 2, ',', ' ') }} {{ $currency }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
    @if($vatRegime === 'marge')
    <div class="vat-note">TVA sur la marge — art. 297A CGI — non récupérable par l'acheteur</div>
    @elseif($vatRegime === 'exempt')
    <div class="vat-note">TVA non applicable — art. 293B CGI — Franchise en base de TVA</div>
    @else
    <div style="margin-bottom:18px;"></div>
    @endif

    {{-- Versements validés --}}
    @php $validatedProofs = $order->proofs->where('status', 'validated'); @endphp
    @if($validatedProofs->count() > 0)
    <div class="section-label">Versements reçus et validés</div>
    <div class="versements-box">
        <table class="versement-row-table">
            @foreach($validatedProofs as $proof)
            <tr class="versement-row">
                <td style="color:#78350f;">
                    Versement du {{ ($proof->reviewed_at ?? $proof->created_at)->format('d/m/Y') }}
                </td>
                <td class="versement-amount">
                    + {{ number_format($proof->amount, 2, ',', ' ') }} {{ $currency }}
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    {{-- Bilan --}}
    <div class="section-label">État du compte</div>
    <table class="bilan-table-outer">
        <tr>
            <td style="width:40%;"></td>
            <td style="width:60%;vertical-align:top;padding-left:12px;">
                <div class="bilan-box">
                    <table class="bilan-row-table">
                        <tr class="bilan-paid">
                            <td>Montant déjà réglé</td>
                            <td class="bilan-paid-amount">{{ number_format($amountPaid, 2, ',', ' ') }} {{ $currency }}</td>
                        </tr>
                        <tr class="bilan-remaining">
                            <td>Reste à payer</td>
                            <td class="bilan-remaining-amount">{{ number_format($amountRemaining, 2, ',', ' ') }} {{ $currency }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- Instructions prochain versement --}}
    @if(!empty($settings['payment_bank_name']))
    <div class="next-payment-box">
        <div class="np-title">Effectuer le prochain versement par virement SEPA</div>
        <div class="pm-method">
            <div class="pm-label">{{ $settings['payment_bank_name'] }}</div>
            @if(!empty($settings['payment_bank_holder']))<div class="pm-value">Titulaire : {{ $settings['payment_bank_holder'] }}</div>@endif
            @if(!empty($settings['payment_bank_iban']))<div class="pm-value">IBAN : {{ $settings['payment_bank_iban'] }}</div>@endif
            @if(!empty($settings['payment_bank_bic']))<div class="pm-value">BIC : {{ $settings['payment_bank_bic'] }}</div>@endif
        </div>
    </div>
    @endif

    {{-- Note --}}
    <div class="note-box">
        <strong>Important :</strong> Ce reçu est provisoire. Soumettez votre preuve de paiement depuis votre espace client après chaque versement.
        La facture définitive vous sera remise uniquement après règlement intégral du montant dû.
        @if(!empty($settings['contact_whatsapp']) || !empty($settings['contact_email']))
            <br>Contact :
            @if(!empty($settings['contact_whatsapp'])) WhatsApp +{{ $settings['contact_whatsapp'] }}@endif
            @if(!empty($settings['contact_email'])) · {{ $settings['contact_email'] }}@endif
        @endif
    </div>

</div>
</body>
</html>
