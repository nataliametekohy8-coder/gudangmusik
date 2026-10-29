<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockTransactionController;
use Illuminate\Support\Facades\Route;

// Halaman awal → login
Route::redirect('/', '/login');

// Rute guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rute auth (sudah login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // === Bisa diakses admin & staff ===
    Route::resource('products', ProductController::class);
    Route::resource('stock-transactions', StockTransactionController::class)
        ->except(['edit', 'update']);

    // === Khusus admin ===
    Route::middleware('role:admin')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('suppliers', SupplierController::class);
    });
});

