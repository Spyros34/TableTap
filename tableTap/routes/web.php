<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\WaiterController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;

Route::get('/', [DashboardController::class, 'index']);
Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen');
Route::get('/waiter', [WaiterController::class, 'index'])->name('waiter');
Route::get('/products', [ProductsController::class, 'index'])->name('products');
Route::get('/qrcode', [QRCodeController::class, 'index'])->name('qrcode');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
