@extends('layouts.admin')

@section('title', 'Preuves de paiement')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold" style="font-size: 1.25rem;">
        Preuves de paiement
        @if($pending->count())
            <span class="ap-badge ms-2" style="background: #fef2f2; color: var(--ap-danger); font-size: .75rem;">{{ $pending->count() }} en attente</span>
        @endif
    </h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" style="font-size: .88rem;" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- En attente --}}
<div class="fw-bold mb-2" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">En attente de validation</div>

<div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden; margin-bottom: 2rem;">
    @if($pending->isEmpty())
        <div class="p-4 text-center text-muted" style="font-size: .88rem;">Aucune preuve en attente.</div>
    @else
        @foreach($pending as $proof)
            <div class="px-4 py-3 d-flex gap-3 align-items-start {{ ! $loop->last ? 'border-bottom' : '' }}"
                 style="{{ ! $loop->last ? 'border-color: var(--ap-border);' : '' }}">

                {{-- Aperçu fichier --}}
                <a href="{{ asset($proof->file_path) }}" target="_blank"
                   style="flex-shrink: 0; width: 56px; height: 56px; background: #f8fafc; border: 1px solid var(--ap-border); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--ap-text-muted); text-decoration: none; overflow: hidden;">
                    @if(str_ends_with(strtolower($proof->file_path), '.pdf'))
                        <i class="bi bi-file-earmark-pdf" style="font-size: 1.4rem; color: #dc2626;"></i>
                    @else
                        <img src="{{ asset($proof->file_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @endif
                </a>

                {{-- Infos --}}
                <div style="flex: 1; min-width: 0;">
                    <div class="d-flex gap-2 align-items-center flex-wrap mb-1">
                        <span class="fw-bold" style="font-size: .95rem; color: var(--ap-accent);">
                            {{ number_format($proof->amount, 2, ',', ' ') }} €
                        </span>
                        <span class="font-monospace" style="font-size: .78rem; color: var(--ap-text-muted);">
                            Commande : {{ $proof->order->order_number }}
                        </span>
                        <span style="font-size: .75rem; color: var(--ap-text-muted);">
                            {{ $proof->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <div style="font-size: .82rem; color: var(--ap-text-muted);">
                        Client : {{ $proof->order->customer_name }} — {{ $proof->order->customer_email }}
                    </div>
                    <div style="font-size: .78rem; margin-top: .25rem;">
                        Total commande : <strong>{{ number_format($proof->order->total, 2, ',', ' ') }} €</strong>
                        &nbsp;|&nbsp;
                        Déjà validé : <strong>{{ number_format($proof->order->amountPaid(), 2, ',', ' ') }} €</strong>
                        &nbsp;|&nbsp;
                        Restant : <strong style="color: var(--ap-danger);">{{ number_format($proof->order->amountRemaining(), 2, ',', ' ') }} €</strong>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-flex flex-column gap-2" style="flex-shrink: 0;">
                    <form action="{{ route('admin.payment-proofs.validate', $proof) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm w-100"
                                style="background: #f0fdf4; color: var(--ap-success); border: 1px solid #bbf7d0; font-size: .8rem;">
                            <i class="bi bi-check-lg me-1"></i>Valider
                        </button>
                    </form>

                    <button type="button" class="btn btn-sm"
                            style="background: #fef2f2; color: var(--ap-danger); border: 1px solid #fecaca; font-size: .8rem;"
                            data-bs-toggle="modal" data-bs-target="#rejectModal{{ $proof->id }}">
                        <i class="bi bi-x-lg me-1"></i>Rejeter
                    </button>
                </div>
            </div>

            {{-- Modal rejet --}}
            <div class="modal fade" id="rejectModal{{ $proof->id }}" tabindex="-1">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <form action="{{ route('admin.payment-proofs.reject', $proof) }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="modal-header">
                                <h6 class="modal-title fw-bold">Rejeter la preuve</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <label class="form-label fw-semibold" style="font-size: .85rem;">Motif du rejet <span class="text-danger">*</span></label>
                                <textarea name="admin_notes" rows="3" class="form-control" required
                                          placeholder="Ex: Montant incorrect, preuve illisible..."></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-ap-outline-primary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-sm" style="background: var(--ap-danger); color: #fff;">Rejeter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

{{-- Traitées récemment --}}
@if($recent->isNotEmpty())
    <div class="fw-bold mb-2" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">Traitées récemment</div>
    <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden;">
        <table class="table mb-0" style="font-size: .83rem;">
            <tbody>
                @foreach($recent as $proof)
                    <tr>
                        <td class="px-4 py-2 font-monospace" style="font-size: .78rem; color: var(--ap-text-muted);">{{ $proof->order->order_number }}</td>
                        <td class="py-2 fw-semibold">{{ number_format($proof->amount, 2, ',', ' ') }} €</td>
                        <td class="py-2">{{ $proof->order->customer_name }}</td>
                        <td class="py-2">
                            @if($proof->status === 'validated')
                                <span class="ap-badge ap-badge-stock">Validée</span>
                            @else
                                <span class="ap-badge ap-badge-oos">Rejetée</span>
                                @if($proof->admin_notes)
                                    <div style="font-size: .72rem; color: var(--ap-text-muted);">{{ $proof->admin_notes }}</div>
                                @endif
                            @endif
                        </td>
                        <td class="py-2 pe-4" style="color: var(--ap-text-muted); font-size: .78rem;">{{ $proof->reviewed_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
