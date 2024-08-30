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

// Public routes
Route::get('/user-selection', function () {
    return Inertia::render('RegisterPages/UserSelection');
})->name('user-selection');

Route::get('/login', function () {
    return Inertia::render('LoginPages/LoginOwner');
})->name('login')->middleware(RedirectIfAuthenticated::class);

Route::get('/login/owner', function () {
    return Inertia::render('LoginPages/LoginOwner');
})->name('login.owner')->middleware(RedirectIfAuthenticated::class);

Route::post('/login/owner', [LoginController::class, 'loginOwner'])->middleware(RedirectIfAuthenticated::class);

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

// Protected routes that require authentication
Route::middleware([EnsureUserIsAuthenticated::class])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen');
    Route::get('/waiter', [WaiterController::class, 'index'])->name('waiter');
    Route::get('/products', [ProductsController::class, 'index'])->name('products');
    Route::get('/qrcode', [QRCodeController::class, 'index'])->name('qrcode');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::get('/create-shop', [ShopController::class, 'create'])->name('create-shop');
    Route::post('/create-shop', [ShopController::class, 'store']);
});