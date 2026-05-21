<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProductController;
use App\Http\Controllers\Public\PartRequestController;
use App\Http\Controllers\Public\CartController;
use App\Http\Controllers\Public\CheckoutController;
use App\Http\Controllers\Public\AccountController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PartRequestController as AdminPartRequestController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\PaymentSettingController;
use App\Http\Controllers\Admin\PaymentProofController as AdminProofController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/part-requests/create', [PartRequestController::class, 'create'])->name('part-requests.create');
Route::post('/part-requests', [PartRequestController::class, 'store'])->name('part-requests.store');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Checkout + account routes (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Account
    Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');
    Route::put('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');

    Route::get('/account/orders', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/account/orders/{order}', [AccountController::class, 'orderShow'])->name('account.orders.show');
    Route::get('/account/orders/{order}/devis', [AccountController::class, 'downloadDevis'])->name('account.orders.devis');
    Route::get('/account/orders/{order}/bon-commande', [AccountController::class, 'downloadBonCommande'])->name('account.orders.bon-commande');
    Route::get('/account/orders/{order}/facture', [AccountController::class, 'downloadFacture'])->name('account.orders.facture');
    Route::get('/account/orders/{order}/recu', [AccountController::class, 'downloadRecu'])->name('account.orders.recu');
    Route::post('/account/orders/{order}/preuves', [AccountController::class, 'submitProof'])->name('account.orders.proof.submit');
});

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout')->middleware('auth');

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', AdminProductController::class);
    Route::resource('categories', AdminCategoryController::class)->except(['show']);

    Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');

    Route::get('payment-settings', [PaymentSettingController::class, 'edit'])->name('payment-settings.edit');
    Route::put('payment-settings', [PaymentSettingController::class, 'update'])->name('payment-settings.update');

    Route::get('payment-proofs', [AdminProofController::class, 'index'])->name('payment-proofs.index');
    Route::patch('payment-proofs/{proof}/validate', [AdminProofController::class, 'validate'])->name('payment-proofs.validate');
    Route::patch('payment-proofs/{proof}/reject', [AdminProofController::class, 'reject'])->name('payment-proofs.reject');

    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('orders/{order}/pdf/devis', [AdminOrderController::class, 'pdfDevis'])->name('orders.pdf.devis');
    Route::get('orders/{order}/pdf/bon-commande', [AdminOrderController::class, 'pdfBonCommande'])->name('orders.pdf.bon-commande');
    Route::get('orders/{order}/pdf/facture', [AdminOrderController::class, 'pdfFacture'])->name('orders.pdf.facture');
    Route::get('orders/{order}/pdf/recu', [AdminOrderController::class, 'pdfRecu'])->name('orders.pdf.recu');

    Route::get('part-requests', [AdminPartRequestController::class, 'index'])->name('part-requests.index');
    Route::get('part-requests/{partRequest}', [AdminPartRequestController::class, 'show'])->name('part-requests.show');
    Route::patch('part-requests/{partRequest}/status', [AdminPartRequestController::class, 'updateStatus'])->name('part-requests.update-status');
});
