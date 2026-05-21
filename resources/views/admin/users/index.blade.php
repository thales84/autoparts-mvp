@extends('layouts.admin')

@section('title', 'Clients inscrits')

@section('content')

<div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden;">

    <div class="px-4 py-3 d-flex justify-content-between align-items-center"
         style="border-bottom: 1px solid var(--ap-border);">
        <span class="fw-bold" style="font-size: .88rem;">
            Clients inscrits
            <span class="text-muted fw-normal ms-1" style="font-size: .8rem;">{{ $users->total() }} au total</span>
        </span>
    </div>

    @if($users->isEmpty())
        <div class="p-5 text-center text-muted">Aucun client inscrit.</div>
    @else
        <table class="table table-hover mb-0" style="font-size: .85rem;">
            <thead style="background: #f8fafc;">
                <tr>
                    <th class="px-4 py-2 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Nom</th>
                    <th class="py-2 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">E-mail</th>
                    <th class="py-2 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Téléphone</th>
                    <th class="py-2 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Statut</th>
                    <th class="py-2 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Commandes</th>
                    <th class="py-2 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Inscrit le</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr onclick="window.location='{{ route('admin.users.show', $user) }}'" style="cursor: pointer;">
                        <td class="px-4 py-2 fw-semibold" style="color: var(--ap-accent);">{{ $user->name }}</td>
                        <td class="py-2">{{ $user->email }}</td>
                        <td class="py-2" style="color: var(--ap-text-muted);">{{ $user->phone ?? '—' }}</td>
                        <td class="py-2">
                            @if($user->status === 'active')
                                <span class="ap-badge ap-badge-stock">Actif</span>
                            @else
                                <span class="ap-badge ap-badge-oos">Bloqué</span>
                            @endif
                        </td>
                        <td class="py-2">
                            @if($user->orders_count)
                                <span class="ap-badge ap-badge-stock">{{ $user->orders_count }}</span>
                            @else
                                <span style="color: var(--ap-text-muted);">0</span>
                            @endif
                        </td>
                        <td class="py-2" style="color: var(--ap-text-muted); font-size: .8rem;">
                            {{ $user->created_at->format('d/m/Y à H:i') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($users->hasPages())
            <div class="px-4 py-3" style="border-top: 1px solid var(--ap-border);">
                {{ $users->links() }}
            </div>
        @endif
    @endif
</div>

@endsection
