<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ImageOptimizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug']     = $this->uniqueSlug($data['name']);
        $data['currency'] = 'EUR';

        if ($request->hasFile('image')) {
            $data['main_image_path'] = $this->storeImage($request->file('image'));
        }

        unset($data['image']);

        Product::create($data);
        Cache::forget('sitemap_xml');

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $this->deleteImage($product->main_image_path);
            $data['main_image_path'] = $this->storeImage($request->file('image'));
        }

        unset($data['image']);

        // Regénère le slug si le nom change
        if ($data['name'] !== $product->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $product->id);
        }

        $product->update($data);
        Cache::forget('sitemap_xml');

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        // Si déjà commandé → désactiver plutôt que supprimer
        if ($product->orderItems()->exists()) {
            $product->update(['status' => 'inactive']);
            Cache::forget('sitemap_xml');

            return redirect()->route('admin.products.index')
                ->with('success', 'Produit désactivé (déjà commandé — suppression impossible).');
        }

        $this->deleteImage($product->main_image_path);
        $product->delete();
        Cache::forget('sitemap_xml');

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit supprimé.');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    private function storeImage(\Illuminate\Http\UploadedFile $file): string
    {
        $destDir = public_path('uploads/products');
        $tmpPath = $file->getRealPath();

        $optimizer = new ImageOptimizer();
        $filename  = $optimizer->optimizeUpload($tmpPath, $destDir);

        return 'uploads/products/' . $filename;
    }

    private function deleteImage(?string $path): void
    {
        if ($path && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }

    private function uniqueSlug(string $name, ?int $exceptId = null): string
    {
        $base  = Str::slug($name);
        $slug  = $base;
        $count = 1;

        while (
            Product::where('slug', $slug)
                ->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
                ->exists()
        ) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }
}
