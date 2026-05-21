@extends('layouts.admin')

@section('title', 'Produits')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold" style="font-size: 1.25rem;">Produits</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-ap-accent btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Nouveau produit
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" style="font-size: .88rem;" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div style="background: #fff; border: 1px solid var(--ap-border); border-radius: var(--ap-radius); overflow: hidden;">
    @if($products->isEmpty())
        <div class="p-5 text-center text-muted" style="font-size: .88rem;">Aucun produit.</div>
    @else
        <table class="table table-hover mb-0" style="font-size: .85rem;">
            <thead style="background: #f8fafc;">
                <tr>
                    <th class="px-4 py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Image</th>
                    <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Produit</th>
                    <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Catégorie</th>
                    <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Prix</th>
                    <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Stock</th>
                    <th class="py-3 fw-semibold" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Statut</th>
                    <th class="py-3 pe-4 fw-semibold text-end" style="font-size: .75rem; text-transform: uppercase; letter-spacing: .4px; color: var(--ap-text-muted);">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td class="px-4 py-2">
                            @if($product->main_image_path)
                                <img src="{{ asset($product->main_image_path) }}"
                                     alt="{{ $product->name }}"
                                     style="width: 44px; height: 44px; object-fit: cover; border-radius: 6px; border: 1px solid var(--ap-border);">
                            @else
                                <div style="width: 44px; height: 44px; background: #f1f5f9; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: var(--ap-text-muted);">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </td>
                        <td class="py-2">
                            <div class="fw-semibold" style="font-size: .87rem;">{{ $product->name }}</div>
                            @if($product->sku)
                                <div class="font-monospace" style="font-size: .72rem; color: var(--ap-text-muted);">{{ $product->sku }}</div>
                            @endif
                        </td>
                        <td class="py-2" style="color: var(--ap-text-muted);">
                            {{ $product->category?->name ?? '—' }}
                        </td>
                        <td class="py-2 fw-semibold">
                            {{ number_format($product->price, 0, ',', ' ') }}
                        </td>
                        <td class="py-2">
                            @if($product->stock_quantity <= 0)
                                <span class="ap-badge ap-badge-oos">{{ $product->stock_quantity }}</span>
                            @elseif($product->stock_quantity <= 3)
                                <span class="ap-badge" style="background: #fff7ed; color: #c2410c;">{{ $product->stock_quantity }}</span>
                            @else
                                <span class="ap-badge ap-badge-stock">{{ $product->stock_quantity }}</span>
                            @endif
                        </td>
                        <td class="py-2">
                            @if($product->status === 'active')
                                <span class="ap-badge ap-badge-stock">Actif</span>
                            @else
                                <span class="ap-badge ap-badge-oos">Inactif</span>
                            @endif
                        </td>
                        <td class="py-2 pe-4 text-end">
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="btn btn-ap-outline-primary btn-sm me-1"
                               style="padding: .25rem .65rem; font-size: .8rem;">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Supprimer ce produit ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm"
                                        style="padding: .25rem .65rem; font-size: .8rem; background: #fef2f2; color: var(--ap-danger); border: 1px solid #fecaca;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($products->hasPages())
            <div class="px-4 py-3" style="border-top: 1px solid var(--ap-border);">
                {{ $products->links() }}
            </div>
        @endif
    @endif
</div>

@endsection
