<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\WaiterController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\WaiterManagerController;
use App\Http\Controllers\KitchenManagerController;
use App\Http\Middleware\EnsureUserIsAuthenticated;
use App\Http\Controllers\Auth\CustomerAuthController;

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
Route::post('/login/kitchen', [LoginController::class, 'loginKitchen'])->middleware('guest');
Route::get('/login/kitchen', function () {
    return Inertia::render('LoginPages/LoginKitchen');
})->name('login.kitchen')->middleware('guest');
Route::get('/kitchen/dashboard', [KitchenManagerController::class, 'index'])->middleware('auth')->name('kitchen.dashboard');
Route::get('/login/waiter', function () {
    // Return a Waiter Login Inertia page or a Blade view
    return Inertia::render('LoginPages/LoginWaiter');
})->name('login.waiter')->middleware('guest');

Route::get('/shops', [ShopController::class, 'list']);

Route::post('/customer/login', [CustomerAuthController::class, 'login']);
Route::post('/customer/register', [CustomerAuthController::class, 'register']);
Route::post('/qr-scan', [TableController::class, 'scan']);
Route::post('/scan-qr', [TableController::class, 'scanQR'])->name('scan.qr');
Route::post('/associate-customer-table', [CustomerController::class, 'associateCustomerTable']);


Route::post('/login/waiter', [LoginController::class, 'loginWaiter'])
    ->middleware('guest');
Route::get('/register', function () {
    return Inertia::render('RegisterPages/RegisterOwner');
})->name('register.owner')->middleware(RedirectIfAuthenticated::class);

Route::post('/register', [RegisterController::class, 'register'])->middleware(RedirectIfAuthenticated::class);
// Route to handle scanning of the QR code (accessible without authentication if needed)
Route::get('/scan', [TableController::class, 'scan'])->name('table.scan');
Route::post('/get-products', [ProductsController::class, 'getProducts']);

Route::post('/get-credit-cards', [CustomerController::class, 'getCreditCards']);
Route::post('/place-order', [CustomerController::class, 'placeOrder']);

// Protected routes that require authentication
Route::middleware([EnsureUserIsAuthenticated::class])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen');
    Route::post('/kitchens', [KitchenController::class, 'store'])->name('kitchen.store');
    Route::put('/kitchens/{id}', [KitchenController::class, 'update'])->name('kitchen.update');
    Route::middleware(['auth:kitchen'])->group(function () {
        Route::get('/kitchen/dashboard', [KitchenManagerController::class, 'index'])->name('kitchen.dashboard');
        Route::post('/kitchen/products/{id}/update', [KitchenManagerController::class, 'updateProduct']);
        Route::post('/kitchen/orders/{id}/ready', [KitchenManagerController::class, 'markOrderAsReady']);
        Route::post('/kitchen/orders/clear-ready', [KitchenManagerController::class, 'clearReadyOrders']);
        Route::get('/kitchen/orders-with-items', [KitchenManagerController::class, 'getOrdersWithItems']);
    });
    // Waiter routes
    Route::get('/waiter', [WaiterController::class, 'index'])->name('waiter');
    Route::post('/waiter', [WaiterController::class, 'store'])->name('waiter.store');
    Route::put('/waiters/{id}', [WaiterController::class, 'update'])->name('waiter.update');
    Route::delete('/waiter/{id}', [WaiterController::class, 'destroy'])->name('waiter.destroy');
   
    // Waiter Manager Routes
   // Waiter manager routes (only accessible if user is authenticated)
   Route::middleware(['auth:waiter'])->group(function () {
    Route::get('/waiter/dashboard', [WaiterManagerController::class, 'index'])->name('waiter.dashboard');
   // Mark a 'ready' order as 'completed'
   Route::post('/waiter/orders/{id}/completed', [WaiterManagerController::class, 'markOrderAsCompleted'])
   ->name('waiter.markOrderAsCompleted');

    // Clear completed orders
    Route::post('/waiter/orders/clear-completed', [WaiterManagerController::class, 'clearCompletedOrders'])
   ->name('waiter.clearCompletedOrders');
    });

    
    

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