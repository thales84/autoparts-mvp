@extends('layouts.admin')

@section('title', 'Catégories')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold" style="font-size: 1.25rem;">Catégories</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-ap-accent btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Nouvelle catégorie
    </a>
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

<div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden;">
    @if($categories->isEmpty())
        <div class="p-5 text-center text-muted" style="font-size: .88rem;">Aucune catégorie.</div>
    @else
        <table class="table table-hover mb-0" style="font-size: .85rem;">
            <thead style="background: #f8fafc;">
                <tr>
                    <th class="px-4 py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Nom</th>
                    <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Slug</th>
                    <th class="py-3 fw-semibold text-center" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Produits</th>
                    <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Statut</th>
                    <th class="py-3 pe-4 fw-semibold text-end" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $cat)
                    <tr>
                        <td class="px-4 py-2 fw-semibold" style="font-size: .87rem;">{{ $cat->name }}</td>
                        <td class="py-2 font-monospace" style="font-size: .78rem; color: var(--ap-text-muted);">{{ $cat->slug }}</td>
                        <td class="py-2 text-center">
                            <span class="ap-badge" style="background: #f1f5f9; color: var(--ap-text-muted);">{{ $cat->products_count }}</span>
                        </td>
                        <td class="py-2">
                            @if($cat->is_active)
                                <span class="ap-badge ap-badge-stock">Active</span>
                            @else
                                <span class="ap-badge ap-badge-oos">Inactive</span>
                            @endif
                        </td>
                        <td class="py-2 pe-4 text-end">
                            <a href="{{ route('admin.categories.edit', $cat) }}"
                               class="btn btn-ap-outline-primary btn-sm me-1"
                               style="padding: .25rem .65rem; font-size: .8rem;">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Supprimer la catégorie « {{ $cat->name }} » ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm"
                                        style="padding: .25rem .65rem; font-size: .8rem; background: #fef2f2; color: var(--ap-danger); border: 1px solid #fecaca;"
                                        @if($cat->products_count > 0) disabled title="Contient {{ $cat->products_count }} produit(s)" @endif>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($categories->hasPages())
            <div class="px-4 py-3" style="border-top: 1px solid var(--ap-border);">
                {{ $categories->links() }}
            </div>
        @endif
    @endif
</div>

@endsection
