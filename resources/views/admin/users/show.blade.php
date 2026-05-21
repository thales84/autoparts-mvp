@extends('layouts.admin')

@section('title', $user->name)

@section('page-actions')
    {{-- Bloquer / Débloquer --}}
    @if($user->status === 'active')
        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalBlock">
            <i class="bi bi-slash-circle me-1"></i>Bloquer
        </button>
    @else
        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalUnblock">
            <i class="bi bi-check-circle me-1"></i>Réactiver
        </button>
    @endif

    {{-- Supprimer --}}
    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete">
        <i class="bi bi-trash me-1"></i>Supprimer
    </button>
@endsection

@section('content')

<div class="row g-4">

    {{-- Profil --}}
    <div class="col-lg-4">
        <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden;">
            <div class="px-4 py-3" style="border-bottom: 1px solid var(--ap-border);">
                <span class="fw-bold" style="font-size: .88rem;">Profil client</span>
            </div>
            <div class="p-4 d-flex flex-column gap-3" style="font-size: .88rem;">

                <div class="d-flex align-items-center justify-content-between">
                    <span style="color: var(--ap-text-muted);">Statut</span>
                    @if($user->status === 'active')
                        <span class="ap-badge ap-badge-stock">Actif</span>
                    @else
                        <span class="ap-badge ap-badge-oos">Bloqué</span>
                    @endif
                </div>

                <div>
                    <div style="color: var(--ap-text-muted); font-size: .75rem; margin-bottom: 2px;">Nom</div>
                    <div class="fw-semibold">{{ $user->name }}</div>
                </div>

                <div>
                    <div style="color: var(--ap-text-muted); font-size: .75rem; margin-bottom: 2px;">E-mail</div>
                    <div>
                        <a href="mailto:{{ $user->email }}" style="color: var(--ap-accent);">{{ $user->email }}</a>
                    </div>
                </div>

                @if($user->phone)
                @php $waPhone = ltrim(preg_replace('/[^\d+]/', '', $user->phone), '+'); @endphp
                <div>
                    <div style="color: var(--ap-text-muted); font-size: .75rem; margin-bottom: 2px;">Téléphone</div>
                    <div class="d-flex align-items-center gap-2">
                        <span>{{ $user->phone }}</span>
                        <a href="https://wa.me/{{ $waPhone }}"
                           target="_blank"
                           class="btn btn-sm"
                           style="background: #25d366; color: #fff; padding: 2px 10px; font-size: .75rem; border-radius: 4px;">
                            <i class="bi bi-whatsapp me-1"></i>WhatsApp
                        </a>
                    </div>
                </div>
                @endif

                <div>
                    <div style="color: var(--ap-text-muted); font-size: .75rem; margin-bottom: 2px;">Inscrit le</div>
                    <div>{{ $user->created_at->format('d/m/Y à H:i') }}</div>
                </div>

                <div>
                    <div style="color: var(--ap-text-muted); font-size: .75rem; margin-bottom: 2px;">Commandes</div>
                    <div class="fw-semibold">{{ $user->orders_count }}</div>
                </div>

                {{-- Relancer --}}
                <div class="pt-2" style="border-top: 1px solid var(--ap-border);">
                    <div style="color: var(--ap-text-muted); font-size: .75rem; margin-bottom: 8px;">Relancer le client</div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="mailto:{{ $user->email }}?subject=Votre%20commande%20chez%20{{ rawurlencode(config('app.name')) }}"
                           class="btn btn-sm btn-ap-outline-primary">
                            <i class="bi bi-envelope me-1"></i>E-mail
                        </a>
                        @if($user->phone)
                        <a href="https://wa.me/{{ $waPhone }}?text={{ rawurlencode('Bonjour ' . $user->name . ', nous vous contactons depuis ' . config('app.name') . '.') }}"
                           target="_blank"
                           class="btn btn-sm"
                           style="background: #25d366; color: #fff;">
                            <i class="bi bi-whatsapp me-1"></i>WhatsApp
                        </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Commandes --}}
    <div class="col-lg-8">
        <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden;">
            <div class="px-4 py-3" style="border-bottom: 1px solid var(--ap-border);">
                <span class="fw-bold" style="font-size: .88rem;">Historique des commandes</span>
            </div>
            @if($orders->isEmpty())
                <div class="p-5 text-center text-muted" style="font-size: .88rem;">Aucune commande.</div>
            @else
                <table class="table table-hover mb-0" style="font-size: .85rem;">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th class="px-4 py-2 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">N°</th>
                            <th class="py-2 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Date</th>
                            <th class="py-2 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Total</th>
                            <th class="py-2 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Paiement</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr onclick="window.location='{{ route('admin.orders.show', $order) }}'" style="cursor: pointer;">
                                <td class="px-4 py-2 font-monospace" style="font-size: .8rem;">{{ $order->order_number }}</td>
                                <td class="py-2" style="color: var(--ap-text-muted);">{{ $order->created_at->format('d/m/Y') }}</td>
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
                @if($orders->hasPages())
                    <div class="px-4 py-3" style="border-top: 1px solid var(--ap-border);">
                        {{ $orders->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

</div>

{{-- ===== MODAL : BLOQUER ===== --}}
<div class="modal fade" id="modalBlock" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content" style="border: none; border-radius: 12px; overflow: hidden;">
            <div class="modal-body p-0">
                <div style="background: #fffbeb; padding: 28px 28px 20px; text-align: center;">
                    <div style="width: 56px; height: 56px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 1.5rem; color: #d97706;">
                        <i class="bi bi-slash-circle"></i>
                    </div>
                    <h5 class="fw-bold mb-1" style="font-size: 1rem; color: #1f2937;">Bloquer ce client ?</h5>
                    <p style="font-size: .88rem; color: #6b7280; margin: 0;">
                        <strong>{{ $user->name }}</strong> ne pourra plus se connecter à son compte.
                        Ses commandes et données sont conservées.
                        Vous pouvez le réactiver à tout moment.
                    </p>
                </div>
                <div class="d-flex gap-2 p-4">
                    <button type="button" class="btn btn-outline-secondary flex-fill" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="flex-fill">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-warning w-100 fw-semibold">
                            <i class="bi bi-slash-circle me-1"></i>Bloquer le compte
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL : RÉACTIVER ===== --}}
<div class="modal fade" id="modalUnblock" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content" style="border: none; border-radius: 12px; overflow: hidden;">
            <div class="modal-body p-0">
                <div style="background: #f0fdf4; padding: 28px 28px 20px; text-align: center;">
                    <div style="width: 56px; height: 56px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 1.5rem; color: #16a34a;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h5 class="fw-bold mb-1" style="font-size: 1rem; color: #1f2937;">Réactiver ce client ?</h5>
                    <p style="font-size: .88rem; color: #6b7280; margin: 0;">
                        <strong>{{ $user->name }}</strong> pourra à nouveau se connecter et passer des commandes.
                    </p>
                </div>
                <div class="d-flex gap-2 p-4">
                    <button type="button" class="btn btn-outline-secondary flex-fill" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="flex-fill">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success w-100 fw-semibold">
                            <i class="bi bi-check-circle me-1"></i>Réactiver le compte
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL : SUPPRIMER ===== --}}
<div class="modal fade" id="modalDelete" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content" style="border: none; border-radius: 12px; overflow: hidden;">
            <div class="modal-body p-0">
                <div style="background: #fef2f2; padding: 28px 28px 20px; text-align: center;">
                    <div style="width: 56px; height: 56px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 1.5rem; color: #dc2626;">
                        <i class="bi bi-trash"></i>
                    </div>
                    <h5 class="fw-bold mb-1" style="font-size: 1rem; color: #1f2937;">Supprimer ce client ?</h5>
                    <p style="font-size: .88rem; color: #6b7280; margin: 0 0 12px;">
                        Le compte de <strong>{{ $user->name }}</strong> sera supprimé définitivement.
                    </p>
                    @if($user->orders_count > 0)
                    <div style="background: #fff7ed; border: 1px solid #fed7aa; border-radius: 8px; padding: 10px 14px; font-size: .82rem; color: #9a3412; text-align: left;">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        Ce client a <strong>{{ $user->orders_count }} commande(s)</strong>.
                        Elles seront conservées mais ne seront plus liées à un compte.
                    </div>
                    @endif
                </div>
                <div class="d-flex gap-2 p-4">
                    <button type="button" class="btn btn-outline-secondary flex-fill" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="flex-fill">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100 fw-semibold">
                            <i class="bi bi-trash me-1"></i>Supprimer définitivement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
