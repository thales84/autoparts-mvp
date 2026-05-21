<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $xml = Cache::remember('sitemap_xml', now()->addHours(6), function () {
            $products = Product::where('status', 'active')
                ->select('slug', 'updated_at')
                ->orderByDesc('updated_at')
                ->get();

            return view('sitemap', compact('products'))->render();
        });

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
