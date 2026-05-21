@extends('layouts.admin')

@section('title', 'Demandes de pièces')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold" style="font-size: 1.25rem;">Demandes de pièces</h1>
    <div style="font-size: .82rem; color: var(--ap-text-muted);">{{ $requests->total() }} demande(s) au total</div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" style="font-size: .88rem;" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden;">
    @if($requests->isEmpty())
        <div class="p-5 text-center text-muted" style="font-size: .88rem;">Aucune demande.</div>
    @else
        <table class="table table-hover mb-0" style="font-size: .85rem;">
            <thead style="background: #f8fafc;">
                <tr>
                    <th class="px-4 py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Pièce demandée</th>
                    <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Contact</th>
                    <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Véhicule</th>
                    <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Statut</th>
                    <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Date</th>
                    <th class="py-3 pe-4 fw-semibold text-end" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                    @php
                        $badgeStyle = match($req->status) {
                            'new'         => 'background:#dbeafe;color:#1e40af',
                            'in_progress' => 'background:#fff7ed;color:#c2410c',
                            'found'       => 'background:#f0fdf4;color:var(--ap-success)',
                            'closed'      => 'background:#f8fafc;color:var(--ap-text-muted)',
                            default       => 'background:#f8fafc;color:var(--ap-text-muted)',
                        };
                        $badgeLabel = match($req->status) {
                            'new'         => 'Nouvelle',
                            'in_progress' => 'En cours',
                            'found'       => 'Trouvée',
                            'closed'      => 'Fermée',
                            default       => $req->status,
                        };
                    @endphp
                    <tr onclick="window.location='{{ route('admin.part-requests.show', $req) }}'" style="cursor: pointer;">
                        <td class="px-4 py-2">
                            <div class="fw-semibold" style="font-size: .87rem;">{{ $req->requested_part_name }}</div>
                        </td>
                        <td class="py-2">
                            <div class="fw-semibold" style="font-size: .87rem;">{{ $req->contact_name }}</div>
                            <div style="font-size: .72rem; color: var(--ap-text-muted);">{{ $req->contact_email }}</div>
                        </td>
                        <td class="py-2" style="color: var(--ap-text-muted); font-size: .82rem;">
                            @if($req->vehicle_make || $req->vehicle_model)
                                {{ implode(' ', array_filter([$req->vehicle_make, $req->vehicle_model, $req->vehicle_year])) }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="py-2">
                            <span class="ap-badge" style="{{ $badgeStyle }}">{{ $badgeLabel }}</span>
                        </td>
                        <td class="py-2" style="color: var(--ap-text-muted); font-size: .8rem;">
                            {{ $req->created_at->format('d/m/Y') }}
                        </td>
                        <td class="py-2 pe-4 text-end" onclick="event.stopPropagation()">
                            <a href="{{ route('admin.part-requests.show', $req) }}"
                               class="btn btn-ap-outline-primary btn-sm"
                               style="padding: .25rem .65rem; font-size: .8rem;">
                                Voir
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($requests->hasPages())
            <div class="px-4 py-3" style="border-top: 1px solid var(--ap-border);">
                {{ $requests->links() }}
            </div>
        @endif
    @endif
</div>

@endsection
