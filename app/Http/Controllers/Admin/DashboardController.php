<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PartRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'products_active'   => Product::where('status', 'active')->count(),
            'products_inactive' => Product::where('status', 'inactive')->count(),
            'orders_total'      => Order::count(),
            'orders_unpaid'     => Order::where('payment_status', 'unpaid')->count(),
            'orders_paid'       => Order::where('payment_status', 'paid')->count(),
            'requests_new'      => PartRequest::where('status', 'new')->count(),
            'requests_total'    => PartRequest::count(),
            'customers_total'   => User::where('role', 'customer')->count(),
        ];

        $recentOrders   = Order::with('user')->latest()->take(5)->get();
        $recentRequests = PartRequest::where('status', 'new')->latest()->take(5)->get();
        $recentUsers    = User::where('role', 'customer')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentRequests', 'recentUsers'));
    }
}
