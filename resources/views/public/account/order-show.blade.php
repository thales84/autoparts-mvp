@extends('layouts.public')

@section('title', 'Commande ' . $order->order_number)

@section('content')
<div class="container py-4" style="max-width: 960px;">

    {{-- En-tête --}}
    <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
        <a href="{{ route('account.orders') }}" style="color: var(--ap-text-muted); text-decoration: none; font-size: .88rem;">
            <i class="bi bi-arrow-left me-1"></i>Mes commandes
        </a>
        <h1 class="fw-bold mb-0 font-monospace" style="font-size: 1.1rem; color: var(--ap-primary);">
            {{ $order->order_number }}
        </h1>
        @php
            $pStyle = match($order->payment_status) {
                'paid'    => 'background:#E8F4EC;color:var(--ap-success)',
                'failed'  => 'background:#F5E8E8;color:var(--ap-danger)',
                default   => 'background:#FEF3C7;color:#92400e',
            };
        @endphp
        <span class="ap-badge" style="{{ $pStyle }}">{{ $order->paymentStatusLabel() }}</span>
        <span style="font-size: .75rem; color: var(--ap-text-muted); margin-left: auto;">
            {{ $order->created_at->format('d/m/Y à H:i') }}
        </span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" style="font-size: .88rem;" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" style="font-size: .88rem;" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ══════ BANDEAU : COMMENT RÉGLER ? ══════ --}}
    @if(! $order->isFullyPaid())
    <div style="background: linear-gradient(135deg, var(--ap-primary) 0%, #2d5499 100%); border-radius: var(--ap-radius); padding: 1.5rem; margin-bottom: 1.5rem;">
        <div class="fw-bold mb-3" style="color: #fff; font-size: .85rem; text-transform: uppercase; letter-spacing: .7px;">
            <i class="bi bi-info-circle-fill me-2"></i>Comment régler votre commande ?
        </div>
        <div class="row g-3">
            <div class="col-md-4">
                <div style="background: rgba(255,255,255,.1); border-radius: 10px; padding: 1rem; height: 100%;">
                    <div style="width: 32px; height: 32px; background: var(--ap-accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; font-size: .85rem; margin-bottom: .75rem;">1</div>
                    <div style="color: #fff; font-weight: 700; font-size: .88rem; margin-bottom: .35rem;">Effectuez votre virement</div>
                    <div style="color: rgba(255,255,255,.75); font-size: .78rem; line-height: 1.55;">
                        Utilisez les coordonnées bancaires indiquées ci-dessous. Mentionnez le n° de commande <strong style="color:#fff;">{{ $order->order_number }}</strong> en référence.
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div style="background: rgba(255,255,255,.1); border-radius: 10px; padding: 1rem; height: 100%;">
                    <div style="width: 32px; height: 32px; background: var(--ap-accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; font-size: .85rem; margin-bottom: .75rem;">2</div>
                    <div style="color: #fff; font-weight: 700; font-size: .88rem; margin-bottom: .35rem;">Téléchargez votre devis</div>
                    <div style="color: rgba(255,255,255,.75); font-size: .78rem; line-height: 1.55;">
                        Disponible ci-dessous. Il contient le récapitulatif complet de votre commande et peut servir de référence pour votre virement.
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div style="background: rgba(255,255,255,.1); border-radius: 10px; padding: 1rem; height: 100%;">
                    <div style="width: 32px; height: 32px; background: var(--ap-accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; font-size: .85rem; margin-bottom: .75rem;">3</div>
                    <div style="color: #fff; font-weight: 700; font-size: .88rem; margin-bottom: .35rem;">Soumettez la preuve</div>
                    <div style="color: rgba(255,255,255,.75); font-size: .78rem; line-height: 1.55;">
                        Après votre virement, envoyez la capture ou le PDF de confirmation via le formulaire ci-dessous. Notre équipe valide sous 24 h.
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Coordonnées bancaires — mobile uniquement, après le bandeau --}}
    @if(!empty($settings['payment_bank_name']) || !empty($settings['payment_bank_iban']))
    <div class="d-lg-none mb-4" style="background: var(--ap-bg-card); border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden;">
        <div class="px-3 py-2" style="background: var(--ap-primary); display: flex; align-items: center; gap: .5rem;">
            <i class="bi bi-bank" style="color: rgba(255,255,255,.8); font-size: 1rem;"></i>
            <span style="color: #fff; font-weight: 700; font-size: .8rem; text-transform: uppercase; letter-spacing: .6px;">Coordonnées bancaires</span>
        </div>
        <div class="p-3">
            <div style="background: #FEF3C7; border: 1px solid #fde68a; border-radius: 8px; padding: .7rem 1rem; margin-bottom: .85rem;">
                <div style="font-size: .72rem; font-weight: 700; color: #92400e; text-transform: uppercase; letter-spacing: .5px; margin-bottom: .2rem;">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>Référence à indiquer dans le virement
                </div>
                <div style="font-family: monospace; font-size: 1rem; font-weight: 700; color: var(--ap-primary);">{{ $order->order_number }}</div>
            </div>
            @if(!empty($settings['payment_bank_name']))
            <div class="mb-2">
                <div style="font-size: .7rem; font-weight: 700; color: var(--ap-text-muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: .2rem;">Banque</div>
                <div style="font-size: .9rem; font-weight: 600; color: var(--ap-text);">{{ $settings['payment_bank_name'] }}</div>
            </div>
            @endif
            @if(!empty($settings['payment_bank_holder']))
            <div class="mb-2">
                <div style="font-size: .7rem; font-weight: 700; color: var(--ap-text-muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: .2rem;">Titulaire</div>
                <div style="font-size: .9rem; font-weight: 600; color: var(--ap-text);">{{ $settings['payment_bank_holder'] }}</div>
            </div>
            @endif
            @if(!empty($settings['payment_bank_iban']))
            <div class="mb-2">
                <div style="font-size: .7rem; font-weight: 700; color: var(--ap-text-muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: .2rem;">IBAN</div>
                <div class="d-flex align-items-center gap-2">
                    <span id="ibanValMobile" style="font-family: monospace; font-size: .88rem; color: var(--ap-text); word-break: break-all; flex: 1;">{{ $settings['payment_bank_iban'] }}</span>
                    <button onclick="copyText('ibanValMobile', this)" title="Copier l'IBAN"
                            style="flex-shrink: 0; background: #EDE8DF; border: 1px solid var(--ap-border); border-radius: 6px; padding: .3rem .55rem; cursor: pointer; color: var(--ap-text-muted);">
                        <i class="bi bi-clipboard" style="font-size: .85rem;"></i>
                    </button>
                </div>
            </div>
            @endif
            @if(!empty($settings['payment_bank_bic']))
            <div class="mb-0">
                <div style="font-size: .7rem; font-weight: 700; color: var(--ap-text-muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: .2rem;">BIC / SWIFT</div>
                <div class="d-flex align-items-center gap-2">
                    <span id="bicValMobile" style="font-family: monospace; font-size: .88rem; color: var(--ap-text); flex: 1;">{{ $settings['payment_bank_bic'] }}</span>
                    <button onclick="copyText('bicValMobile', this)" title="Copier le BIC"
                            style="flex-shrink: 0; background: #EDE8DF; border: 1px solid var(--ap-border); border-radius: 6px; padding: .3rem .55rem; cursor: pointer; color: var(--ap-text-muted);">
                        <i class="bi bi-clipboard" style="font-size: .85rem;"></i>
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <div class="row g-4">

        {{-- ══════ COLONNE PRINCIPALE ══════ --}}
        <div class="col-lg-7">

            {{-- Articles --}}
            <div style="background: var(--ap-bg-card); border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden; margin-bottom: 1.25rem;">
                <div class="px-4 py-3 fw-bold" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted); border-bottom: 1px solid var(--ap-border);">
                    Articles ({{ $order->items->count() }})
                </div>
                <table class="table mb-0" style="font-size: .87rem;">
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="fw-semibold">{{ $item->product_name }}</div>
                                    @if($item->product_sku)
                                        <div class="font-monospace" style="font-size: .72rem; color: var(--ap-text-muted);">{{ $item->product_sku }}</div>
                                    @endif
                                </td>
                                <td class="py-3 text-center" style="color: var(--ap-text-muted);">× {{ $item->quantity }}</td>
                                <td class="py-3 pe-4 text-end fw-semibold">
                                    {{ number_format($item->line_total, 2, ',', ' ') }} €
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="border-top: 2px solid var(--ap-border);">
                        <tr>
                            <td colspan="2" class="px-4 py-3 fw-bold text-end">Total</td>
                            <td class="py-3 pe-4 fw-bold text-end" style="font-size: 1.05rem; color: var(--ap-accent);">
                                {{ number_format($order->total, 2, ',', ' ') }} €
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Preuves de paiement soumises --}}
            @if($order->proofs->isNotEmpty())
                <div style="background: var(--ap-bg-card); border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden; margin-bottom: 1.25rem;">
                    <div class="px-4 py-3 fw-bold" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted); border-bottom: 1px solid var(--ap-border);">
                        Mes paiements soumis
                    </div>
                    @foreach($order->proofs as $proof)
                        @php
                            $ps = match($proof->status) {
                                'validated' => 'background:#E8F4EC;color:var(--ap-success)',
                                'rejected'  => 'background:#F5E8E8;color:var(--ap-danger)',
                                default     => 'background:#FEF3C7;color:#92400e',
                            };
                        @endphp
                        <div class="px-4 py-3 d-flex gap-3 align-items-start {{ ! $loop->last ? 'border-bottom' : '' }}"
                             style="{{ ! $loop->last ? 'border-color: var(--ap-border);' : '' }}">
                            <a href="{{ asset($proof->file_path) }}" target="_blank"
                               style="flex-shrink: 0; width: 44px; height: 44px; background: #EDE8DF; border: 1px solid var(--ap-border); border-radius: 6px; display: flex; align-items: center; justify-content: center; text-decoration: none; overflow: hidden;">
                                @if(str_ends_with(strtolower($proof->file_path), '.pdf'))
                                    <i class="bi bi-file-earmark-pdf" style="color: #dc2626; font-size: 1.2rem;"></i>
                                @else
                                    <img src="{{ asset($proof->file_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @endif
                            </a>
                            <div style="flex: 1;">
                                <div class="d-flex gap-2 align-items-center flex-wrap">
                                    <span class="fw-bold">{{ number_format($proof->amount, 2, ',', ' ') }} €</span>
                                    <span class="ap-badge" style="{{ $ps }}">{{ $proof->statusLabel() }}</span>
                                    <span style="font-size: .75rem; color: var(--ap-text-muted);">{{ $proof->created_at->format('d/m/Y') }}</span>
                                </div>
                                @if($proof->status === 'rejected' && $proof->admin_notes)
                                    <div style="font-size: .8rem; color: var(--ap-danger); margin-top: .3rem; background: #F5E8E8; padding: .4rem .6rem; border-radius: 5px;">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $proof->admin_notes }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Upload preuve --}}
            @if(! $order->isFullyPaid())
                <div style="background: var(--ap-bg-card); border: 1.5px solid var(--ap-accent); border-radius: var(--ap-radius); padding: 1.5rem;">
                    <div class="fw-bold mb-3" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-accent);">
                        <i class="bi bi-upload me-1"></i>Soumettre une preuve de paiement
                    </div>
                    <p style="font-size: .82rem; color: var(--ap-text-muted); margin-bottom: 1rem;">
                        Après votre virement, joignez la capture ou le PDF de confirmation de votre banque. Notre équipe la validera sous 24 h.
                    </p>
                    <form action="{{ route('account.orders.proof.submit', $order) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-sm-5">
                                <label class="form-label fw-semibold" style="font-size: .85rem;">Montant versé (€) <span class="text-danger">*</span></label>
                                <input type="number" name="amount" min="1" max="{{ $order->amountRemaining() }}"
                                       class="form-control @error('amount') is-invalid @enderror"
                                       placeholder="{{ number_format($order->amountRemaining(), 2, ',', ' ') }}"
                                       value="{{ old('amount') }}" required>
                                @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-7">
                                <label class="form-label fw-semibold" style="font-size: .85rem;">Preuve (photo ou PDF) <span class="text-danger">*</span></label>
                                <input type="file" name="file" accept="image/*,.pdf"
                                       class="form-control @error('file') is-invalid @enderror" required>
                                @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-ap-accent">
                                <i class="bi bi-send me-1"></i>Envoyer la preuve
                            </button>
                        </div>
                    </form>
                </div>
            @endif

        </div>

        {{-- ══════ COLONNE DROITE ══════ --}}
        <div class="col-lg-5">

            {{-- Documents --}}
            <div style="background: var(--ap-bg-card); border: 1px solid var(--ap-border); border-radius: var(--ap-radius); padding: 1.25rem; margin-bottom: 1rem;">
                <div class="fw-bold mb-3" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">
                    <i class="bi bi-file-earmark-arrow-down me-1"></i>Documents à télécharger
                </div>
                <div class="d-flex flex-column gap-2">

                    {{-- Devis --}}
                    <a href="{{ route('account.orders.devis', $order) }}"
                       class="d-flex align-items-center gap-3 p-2 rounded text-decoration-none"
                       style="background: #EDE8DF; border: 1px solid var(--ap-border);">
                        <div style="width: 34px; height: 34px; background: var(--ap-primary); border-radius: 7px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="bi bi-file-earmark-text" style="color: #fff; font-size: 1rem;"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-size: .85rem; font-weight: 700; color: var(--ap-text);">Devis</div>
                            <div style="font-size: .72rem; color: var(--ap-text-muted);">Toujours disponible</div>
                        </div>
                        <i class="bi bi-download" style="color: var(--ap-text-muted); font-size: .85rem;"></i>
                    </a>

                    {{-- Bon de commande --}}
                    @if($order->isFullyPaid() || $order->payment_status === 'paid')
                        <a href="{{ route('account.orders.bon-commande', $order) }}"
                           class="d-flex align-items-center gap-3 p-2 rounded text-decoration-none"
                           style="background: #EDE8DF; border: 1px solid var(--ap-border);">
                            <div style="width: 34px; height: 34px; background: #2563eb; border-radius: 7px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="bi bi-bag-check" style="color: #fff; font-size: 1rem;"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-size: .85rem; font-weight: 700; color: var(--ap-text);">Bon de commande</div>
                                <div style="font-size: .72rem; color: var(--ap-text-muted);">Confirmation de commande</div>
                            </div>
                            <i class="bi bi-download" style="color: var(--ap-text-muted); font-size: .85rem;"></i>
                        </a>
                    @else
                        <div class="d-flex align-items-center gap-3 p-2 rounded"
                             style="background: var(--ap-bg-card); border: 1px dashed var(--ap-border); opacity: .6;">
                            <div style="width: 34px; height: 34px; background: #d1d5db; border-radius: 7px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="bi bi-bag-check" style="color: #fff; font-size: 1rem;"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-size: .85rem; font-weight: 700; color: var(--ap-text-muted);">Bon de commande</div>
                                <div style="font-size: .72rem; color: var(--ap-text-muted);">Disponible après paiement validé</div>
                            </div>
                            <i class="bi bi-lock" style="color: var(--ap-text-muted); font-size: .85rem;"></i>
                        </div>
                    @endif

                    {{-- Reçu / Facture --}}
                    @if($order->isFullyPaid())
                        <a href="{{ route('account.orders.facture', $order) }}"
                           class="d-flex align-items-center gap-3 p-2 rounded text-decoration-none"
                           style="background: #E8F4EC; border: 1px solid #B8DEC5;">
                            <div style="width: 34px; height: 34px; background: #059669; border-radius: 7px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="bi bi-file-earmark-check" style="color: #fff; font-size: 1rem;"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-size: .85rem; font-weight: 700; color: var(--ap-text);">Facture</div>
                                <div style="font-size: .72rem; color: var(--ap-text-muted);">Paiement complet confirmé</div>
                            </div>
                            <i class="bi bi-download" style="color: var(--ap-success); font-size: .85rem;"></i>
                        </a>
                    @elseif($order->proofs->where('status','validated')->count() > 0)
                        <a href="{{ route('account.orders.recu', $order) }}"
                           class="d-flex align-items-center gap-3 p-2 rounded text-decoration-none"
                           style="background: #FEF3C7; border: 1px solid #fde68a;">
                            <div style="width: 34px; height: 34px; background: #d97706; border-radius: 7px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="bi bi-receipt" style="color: #fff; font-size: 1rem;"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-size: .85rem; font-weight: 700; color: var(--ap-text);">Reçu de versement(s)</div>
                                <div style="font-size: .72rem; color: var(--ap-text-muted);">Avec solde restant à payer</div>
                            </div>
                            <i class="bi bi-download" style="color: #d97706; font-size: .85rem;"></i>
                        </a>
                    @else
                        <div class="d-flex align-items-center gap-3 p-2 rounded"
                             style="background: var(--ap-bg-card); border: 1px dashed var(--ap-border); opacity: .6;">
                            <div style="width: 34px; height: 34px; background: #d1d5db; border-radius: 7px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="bi bi-file-earmark-check" style="color: #fff; font-size: 1rem;"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-size: .85rem; font-weight: 700; color: var(--ap-text-muted);">Facture / Reçu</div>
                                <div style="font-size: .72rem; color: var(--ap-text-muted);">Disponible après validation</div>
                            </div>
                            <i class="bi bi-lock" style="color: var(--ap-text-muted); font-size: .85rem;"></i>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Progression paiement --}}
            @php
                $paid      = $order->amountPaid();
                $total     = (float) $order->total;
                $remaining = $order->amountRemaining();
                $pct       = $total > 0 ? min(100, round($paid / $total * 100)) : 0;
            @endphp
            <div style="background: var(--ap-bg-card); border: 1px solid var(--ap-border); border-radius: var(--ap-radius); padding: 1.25rem; margin-bottom: 1rem;">
                <div class="fw-bold mb-3" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">Progression du paiement</div>
                <div style="background: #EDE8DF; border-radius: 99px; height: 10px; margin-bottom: .75rem; overflow: hidden;">
                    <div style="width: {{ $pct }}%; height: 100%; background: {{ $pct >= 100 ? 'var(--ap-success)' : 'var(--ap-accent)' }}; border-radius: 99px; transition: width .5s;"></div>
                </div>
                <div class="d-flex justify-content-between" style="font-size: .82rem;">
                    <span style="color: var(--ap-success);">Payé : <strong>{{ number_format($paid, 2, ',', ' ') }} €</strong></span>
                    @if($remaining > 0)
                        <span style="color: var(--ap-danger);">Reste : <strong>{{ number_format($remaining, 2, ',', ' ') }} €</strong></span>
                    @else
                        <span style="color: var(--ap-success); font-weight: 700;"><i class="bi bi-check-circle-fill me-1"></i>Soldé</span>
                    @endif
                </div>
            </div>

            {{-- Coordonnées bancaires — desktop uniquement --}}
            @if(!empty($settings['payment_bank_name']) || !empty($settings['payment_bank_iban']))
            <div class="d-none d-lg-block" style="background: var(--ap-bg-card); border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden; margin-bottom: 1rem;">
                <div class="px-3 py-2" style="background: var(--ap-primary); display: flex; align-items: center; gap: .5rem;">
                    <i class="bi bi-bank" style="color: rgba(255,255,255,.8); font-size: 1rem;"></i>
                    <span style="color: #fff; font-weight: 700; font-size: .8rem; text-transform: uppercase; letter-spacing: .6px;">Coordonnées bancaires</span>
                </div>
                <div class="p-3">

                    {{-- Référence à indiquer --}}
                    <div style="background: #FEF3C7; border: 1px solid #fde68a; border-radius: 8px; padding: .7rem 1rem; margin-bottom: .85rem;">
                        <div style="font-size: .72rem; font-weight: 700; color: #92400e; text-transform: uppercase; letter-spacing: .5px; margin-bottom: .2rem;">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>Référence à indiquer dans le virement
                        </div>
                        <div style="font-family: monospace; font-size: 1rem; font-weight: 700; color: var(--ap-primary);">{{ $order->order_number }}</div>
                    </div>

                    {{-- Champs bancaires --}}
                    @if(!empty($settings['payment_bank_name']))
                    <div class="mb-2">
                        <div style="font-size: .7rem; font-weight: 700; color: var(--ap-text-muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: .2rem;">Banque</div>
                        <div style="font-size: .9rem; font-weight: 600; color: var(--ap-text);">{{ $settings['payment_bank_name'] }}</div>
                    </div>
                    @endif

                    @if(!empty($settings['payment_bank_holder']))
                    <div class="mb-2">
                        <div style="font-size: .7rem; font-weight: 700; color: var(--ap-text-muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: .2rem;">Titulaire</div>
                        <div style="font-size: .9rem; font-weight: 600; color: var(--ap-text);">{{ $settings['payment_bank_holder'] }}</div>
                    </div>
                    @endif

                    @if(!empty($settings['payment_bank_iban']))
                    <div class="mb-2">
                        <div style="font-size: .7rem; font-weight: 700; color: var(--ap-text-muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: .2rem;">IBAN</div>
                        <div class="d-flex align-items-center gap-2">
                            <span id="ibanVal" style="font-family: monospace; font-size: .88rem; color: var(--ap-text); word-break: break-all; flex: 1;">{{ $settings['payment_bank_iban'] }}</span>
                            <button onclick="copyText('ibanVal', this)"
                                    title="Copier l'IBAN"
                                    style="flex-shrink: 0; background: #EDE8DF; border: 1px solid var(--ap-border); border-radius: 6px; padding: .3rem .55rem; cursor: pointer; color: var(--ap-text-muted);">
                                <i class="bi bi-clipboard" style="font-size: .85rem;"></i>
                            </button>
                        </div>
                    </div>
                    @endif

                    @if(!empty($settings['payment_bank_bic']))
                    <div class="mb-0">
                        <div style="font-size: .7rem; font-weight: 700; color: var(--ap-text-muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: .2rem;">BIC / SWIFT</div>
                        <div class="d-flex align-items-center gap-2">
                            <span id="bicVal" style="font-family: monospace; font-size: .88rem; color: var(--ap-text); flex: 1;">{{ $settings['payment_bank_bic'] }}</span>
                            <button onclick="copyText('bicVal', this)"
                                    title="Copier le BIC"
                                    style="flex-shrink: 0; background: #EDE8DF; border: 1px solid var(--ap-border); border-radius: 6px; padding: .3rem .55rem; cursor: pointer; color: var(--ap-text-muted);">
                                <i class="bi bi-clipboard" style="font-size: .85rem;"></i>
                            </button>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- ══════ CONTACT ÉQUIPE — BAS DE PAGE ══════ --}}
    @php
        $whatsapp = $settings['contact_whatsapp'] ?? '';
        $email    = $settings['contact_email'] ?? '';
        $msgWA    = rawurlencode(
            "Bonjour,\n\nJe suis " . auth()->user()->name .
            " (" . auth()->user()->email . ")" .
            (auth()->user()->phone ? " / " . auth()->user()->phone : "") .
            ".\n\nJe vous contacte concernant ma commande n°" . $order->order_number .
            " (montant : " . number_format($order->total, 2, ',', ' ') . " €" .
            ", reste à payer : " . number_format($order->amountRemaining(), 2, ',', ' ') . " €).\n\nMerci."
        );
        $subject  = rawurlencode("Commande " . $order->order_number);
        $bodyMail = rawurlencode(
            "Bonjour,\n\nJe suis " . auth()->user()->name .
            " (" . auth()->user()->email . ")" .
            (auth()->user()->phone ? " / " . auth()->user()->phone : "") .
            ".\n\nMa commande : " . $order->order_number .
            "\nMontant total : " . number_format($order->total, 2, ',', ' ') . " €" .
            "\nReste à payer : " . number_format($order->amountRemaining(), 2, ',', ' ') . " €\n\nMessage :\n"
        );
    @endphp
    @if($whatsapp || $email)
    <div style="background: var(--ap-bg-card); border: 1px solid var(--ap-border); border-radius: var(--ap-radius); padding: 1.5rem; margin-top: .5rem; text-align: center;">
        <div class="fw-bold mb-1" style="font-size: 1rem; color: var(--ap-text);">Une question sur votre commande ?</div>
        <p style="font-size: .85rem; color: var(--ap-text-muted); margin-bottom: 1.25rem;">
            Notre équipe est disponible pour vous accompagner dans votre paiement ou pour toute question.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            @if($whatsapp)
                <a href="https://wa.me/{{ $whatsapp }}?text={{ $msgWA }}"
                   target="_blank"
                   class="btn d-flex align-items-center gap-2"
                   style="background: #25d366; color: #fff; border: none; font-size: .92rem; padding: .65rem 1.4rem; border-radius: 10px; font-weight: 600;">
                    <i class="bi bi-whatsapp" style="font-size: 1.1rem;"></i>
                    Contacter via WhatsApp
                </a>
            @endif
            @if($email)
                <a href="mailto:{{ $email }}?subject={{ $subject }}&body={{ $bodyMail }}"
                   class="btn btn-ap-outline-primary d-flex align-items-center gap-2"
                   style="font-size: .92rem; padding: .65rem 1.4rem; border-radius: 10px; font-weight: 600;">
                    <i class="bi bi-envelope" style="font-size: 1.1rem;"></i>
                    Envoyer un e-mail
                </a>
            @endif
        </div>
    </div>
    @endif

</div>

<script>
function copyText(id, btn) {
    const text = document.getElementById(id).textContent.trim();
    navigator.clipboard.writeText(text).then(() => {
        const icon = btn.querySelector('i');
        icon.className = 'bi bi-clipboard-check';
        btn.style.color = 'var(--ap-success)';
        setTimeout(() => {
            icon.className = 'bi bi-clipboard';
            btn.style.color = '';
        }, 2000);
    });
}
</script>

@endsection
