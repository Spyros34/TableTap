<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\WaiterController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShopController;
use App\Http\Middleware\EnsureUserIsAuthenticated;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\TableController;

// Public routes
Route::get('/user-selection', function () {
    return Inertia::render('RegisterPages/UserSelection');
})->name('user-selection');

Route::get('/login', function () {
    return Inertia::render('RegisterPages/UserSelection');
})->name('login')->middleware(RedirectIfAuthenticated::class);

Route::get('/login/owner', function () {
    return Inertia::render('LoginPages/LoginOwner');
})->name('login.owner')->middleware('guest');

Route::post('/login/owner', [LoginController::class, 'loginOwner'])->middleware('guest');
Route::get('/login/kitchen', function () {
    return Inertia::render('LoginPages/LoginKitchen');
})->name('login.kitchen')->middleware(RedirectIfAuthenticated::class);

Route::post('/login/kitchen', [LoginController::class, 'loginKitchen'])->middleware(RedirectIfAuthenticated::class);

Route::get('/login/waiter', function () {
    return Inertia::render('LoginPages/LoginWaiter');
})->name('login.waiter')->middleware(RedirectIfAuthenticated::class);

Route::post('/login/waiter', [LoginController::class, 'loginWaiter'])->middleware(RedirectIfAuthenticated::class);

Route::get('/register', function () {
    return Inertia::render('RegisterPages/RegisterOwner');
})->name('register.owner')->middleware(RedirectIfAuthenticated::class);

Route::post('/register', [RegisterController::class, 'register'])->middleware(RedirectIfAuthenticated::class);
// Route to handle scanning of the QR code (accessible without authentication if needed)
Route::get('/scan', [TableController::class, 'scan'])->name('table.scan');

// Protected routes that require authentication
Route::middleware([EnsureUserIsAuthenticated::class])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Owner/Dashboard');
    })->name('dashboard');
    Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen');
    Route::post('/kitchens', [KitchenController::class, 'store'])->name('kitchen.store');
    Route::put('/kitchens/{id}', [KitchenController::class, 'update'])->name('kitchen.update');
    // Waiter routes
    Route::get('/waiter', [WaiterController::class, 'index'])->name('waiter');
    Route::post('/waiter', [WaiterController::class, 'store'])->name('waiter.store');
    Route::put('/waiters/{id}', [WaiterController::class, 'update'])->name('waiter.update');
    Route::delete('/waiter/{id}', [WaiterController::class, 'destroy'])->name('waiter.destroy');

    Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductsController::class, 'store'])->name('products.store');
    Route::put('/products/{id}', [ProductsController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductsController::class, 'destroy'])->name('products.destroy');
    Route::get('/qrcode', [QRCodeController::class, 'index'])->name('qrcode');
    Route::get('/owner/profile', [ProfileController::class, 'index'])->name('owner.profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/kitchen/{id}', [KitchenController::class, 'destroy'])->name('kitchen.destroy');
    Route::put('/password/update', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::get('/create-shop', function () {
        return Inertia::render('Owner/CreateShop');
    })->name('create-shop');
    Route::post('/create-shop', [ShopController::class, 'store']);

    // Route to handle table creation and QR code generation
    Route::post('/tables', [TableController::class, 'store'])->name('tables.store');
    Route::get('/tables', [TableController::class, 'index'])->name('tables.index');
    Route::put('/tables/{id}', [TableController::class, 'update'])->name('tables.update');
    Route::delete('/tables/{id}', [TableController::class, 'destroy'])->name('tables.destroy');
    // Route to serve the QR code image
    Route::get('/tables/{id}/qrcode', [TableController::class, 'showQRCodeImage'])->name('table.qrcode');
});