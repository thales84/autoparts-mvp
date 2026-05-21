@extends('layouts.admin')

@section('title', 'Demande de pièce')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.part-requests.index') }}" style="color: var(--ap-text-muted); text-decoration: none; font-size: .88rem;">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
    <h1 class="fw-bold mb-0" style="font-size: 1.15rem;">Demande #{{ $partRequest->id }}</h1>
    @php
        $badgeStyle = match($partRequest->status) {
            'new'         => 'background:#dbeafe;color:#1e40af',
            'in_progress' => 'background:#fff7ed;color:#c2410c',
            'found'       => 'background:#f0fdf4;color:var(--ap-success)',
            'closed'      => 'background:#f8fafc;color:var(--ap-text-muted)',
            default       => 'background:#f8fafc;color:var(--ap-text-muted)',
        };
        $badgeLabel = match($partRequest->status) {
            'new'         => 'Nouvelle',
            'in_progress' => 'En cours',
            'found'       => 'Trouvée',
            'closed'      => 'Fermée',
            default       => $partRequest->status,
        };
    @endphp
    <span class="ap-badge" style="{{ $badgeStyle }}">{{ $badgeLabel }}</span>
    <span style="font-size: .75rem; color: var(--ap-text-muted); margin-left: auto;">
        {{ $partRequest->created_at->format('d/m/Y à H:i') }}
    </span>
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

        {{-- Pièce demandée --}}
        <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); padding: 1.5rem; margin-bottom: 1.25rem;">
            <div class="fw-bold mb-3" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">Pièce demandée</div>
            <div class="fw-bold mb-2" style="font-size: 1.05rem; color: var(--ap-primary);">{{ $partRequest->requested_part_name }}</div>

            @if($partRequest->vehicle_make || $partRequest->vehicle_model || $partRequest->vehicle_year)
                <div class="d-flex gap-3 flex-wrap mb-3">
                    @if($partRequest->vehicle_make)
                        <div>
                            <div style="font-size: .72rem; color: var(--ap-text-muted);">Marque</div>
                            <div class="fw-semibold" style="font-size: .87rem;">{{ $partRequest->vehicle_make }}</div>
                        </div>
                    @endif
                    @if($partRequest->vehicle_model)
                        <div>
                            <div style="font-size: .72rem; color: var(--ap-text-muted);">Modèle</div>
                            <div class="fw-semibold" style="font-size: .87rem;">{{ $partRequest->vehicle_model }}</div>
                        </div>
                    @endif
                    @if($partRequest->vehicle_year)
                        <div>
                            <div style="font-size: .72rem; color: var(--ap-text-muted);">Année</div>
                            <div class="fw-semibold" style="font-size: .87rem;">{{ $partRequest->vehicle_year }}</div>
                        </div>
                    @endif
                </div>
            @endif

            @if($partRequest->description)
                <div style="font-size: .75rem; color: var(--ap-text-muted); margin-bottom: .3rem;">Description / précisions</div>
                <div style="font-size: .87rem; background: #f8fafc; padding: .75rem 1rem; border-radius: 6px; border-left: 3px solid var(--ap-border);">
                    {{ $partRequest->description }}
                </div>
            @endif
        </div>

        {{-- Contact --}}
        <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); padding: 1.5rem;">
            <div class="fw-bold mb-3" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">Contact</div>
            <div class="row g-3">
                <div class="col-sm-6">
                    <div style="font-size: .72rem; color: var(--ap-text-muted); margin-bottom: .2rem;">Nom</div>
                    <div class="fw-semibold" style="font-size: .87rem;">{{ $partRequest->contact_name }}</div>
                </div>
                @if($partRequest->contact_email)
                    <div class="col-sm-6">
                        <div style="font-size: .72rem; color: var(--ap-text-muted); margin-bottom: .2rem;">Email</div>
                        <div style="font-size: .87rem;">
                            <a href="mailto:{{ $partRequest->contact_email }}" style="color: var(--ap-primary);">
                                {{ $partRequest->contact_email }}
                            </a>
                        </div>
                    </div>
                @endif
                @if($partRequest->contact_phone)
                    <div class="col-sm-6">
                        <div style="font-size: .72rem; color: var(--ap-text-muted); margin-bottom: .2rem;">Téléphone</div>
                        <div style="font-size: .87rem;">
                            <a href="tel:{{ $partRequest->contact_phone }}" style="color: var(--ap-primary);">
                                {{ $partRequest->contact_phone }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Colonne latérale --}}
    <div class="col-lg-4">

        {{-- Traitement --}}
        <div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); padding: 1.5rem;">
            <div class="fw-bold mb-3" style="font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: var(--ap-text-muted);">Traitement</div>

            <form action="{{ route('admin.part-requests.update-status', $partRequest) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size: .82rem;">Statut</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="new"         {{ $partRequest->status === 'new'         ? 'selected' : '' }}>Nouvelle</option>
                        <option value="in_progress" {{ $partRequest->status === 'in_progress' ? 'selected' : '' }}>En cours</option>
                        <option value="found"       {{ $partRequest->status === 'found'       ? 'selected' : '' }}>Pièce trouvée</option>
                        <option value="closed"      {{ $partRequest->status === 'closed'      ? 'selected' : '' }}>Fermée</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size: .82rem;">Notes internes</label>
                    <textarea name="admin_notes" rows="6"
                              class="form-control form-control-sm @error('admin_notes') is-invalid @enderror"
                              placeholder="Prix estimé, disponibilité, notes de suivi...">{{ old('admin_notes', $partRequest->admin_notes) }}</textarea>
                    @error('admin_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-ap-accent btn-sm w-100">
                    <i class="bi bi-check-lg me-1"></i>Enregistrer
                </button>
            </form>

            @if($partRequest->admin_notes && ! old('admin_notes'))
                <div class="mt-3 pt-3" style="border-top: 1px solid var(--ap-border);">
                    <div style="font-size: .72rem; color: var(--ap-text-muted); margin-bottom: .3rem;">Notes actuelles</div>
                    <div style="font-size: .82rem; background: #f8fafc; padding: .6rem .75rem; border-radius: 6px;">
                        {{ $partRequest->admin_notes }}
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>

@endsection
