<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhooks\RelBaseWebhookController;
use App\Http\Controllers\Webhooks\MercadoPagoWebhookController;
use App\Livewire\Store\Home;
use App\Livewire\Store\Shop;
use App\Livewire\Store\Services;

// Public Storefront Routes
Route::get('/', Home::class)->name('home');
Route::get('tienda', Shop::class)->name('shop');
Route::get('servicios', Services::class)->name('services');

// Google Authentication Routes
Route::get('auth/google', [\App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Set Password Flow (Google users)
Route::middleware(['auth'])->group(function () {
    Route::get('set-password', \App\Livewire\Auth\SetPassword::class)->name('password.set');
});

// Webhook Routes (CSRF Excluded)
Route::post('webhooks/relbase', RelBaseWebhookController::class)->name('webhooks.relbase');
Route::post('webhooks/mercadopago', MercadoPagoWebhookController::class)->name('webhooks.mercadopago');

Route::middleware(['auth', 'verified'])->get('dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    $orders = auth()->user()->orders()->with('items.product')->latest()->get();
    return view('dashboard', compact('orders'));
})->name('dashboard');

// Administrative Panel Routes (Protected)
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
    Route::get('productos', \App\Livewire\Admin\Products::class)->name('admin.products');
    Route::get('categorias', \App\Livewire\Admin\Categories::class)->name('admin.categories');
    Route::get('almacenes', \App\Livewire\Admin\Warehouses::class)->name('admin.warehouses');
    Route::get('inventario', \App\Livewire\Admin\Inventory::class)->name('admin.inventory');
    Route::get('alertas', \App\Livewire\Admin\Alerts::class)->name('admin.alerts');
    Route::get('marca', \App\Livewire\Admin\Branding::class)->name('admin.branding');
    Route::get('pedidos', \App\Livewire\Admin\Orders::class)->name('admin.orders');
    Route::get('reportes', \App\Livewire\Admin\Reports::class)->name('admin.reports');
    Route::get('usuarios', \App\Livewire\Admin\Users::class)->name('admin.users');
    Route::get('sistema', \App\Livewire\Admin\System::class)->name('admin.system');
});

require __DIR__.'/settings.php';
