<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'images'])
            ->where('status', 'active');

        if ($q = $request->input('q')) {
            $like = '%' . $q . '%';
            $query->where(function ($sub) use ($like) {
                $sub->where('name', 'LIKE', $like)
                    ->orWhere('sku', 'LIKE', $like)
                    ->orWhere('oem_reference', 'LIKE', $like)
                    ->orWhere('description', 'LIKE', $like);
            });
        }

        if ($categoryId = $request->input('category')) {
            $query->where('category_id', $categoryId);
        }

        if ($makeId = $request->input('make')) {
            $query->whereHas('compatibilities', function ($sub) use ($makeId) {
                $sub->where('vehicle_make_id', $makeId);
            });
        }

        if ($modelId = $request->input('model')) {
            $query->whereHas('compatibilities', function ($sub) use ($modelId) {
                $sub->where('vehicle_model_id', $modelId);
            });
        }

        $products    = $query->latest()->paginate(12)->withQueryString();
        $categories  = Category::where('is_active', true)->orderBy('name')->get();
        $makes       = VehicleMake::orderBy('name')->get();

        $selectedModels = collect();
        if ($makeId = $request->input('make')) {
            $selectedModels = VehicleModel::where('vehicle_make_id', $makeId)->orderBy('name')->get();
        }

        return view('public.products.index', compact(
            'products',
            'categories',
            'makes',
            'selectedModels'
        ));
    }

    public function show(Product $product): View
    {
        if ($product->status !== 'active') {
            abort(404);
        }

        $product->load(['category', 'images', 'compatibilities.vehicleMake', 'compatibilities.vehicleModel']);

        return view('public.products.show', compact('product'));
    }
}
