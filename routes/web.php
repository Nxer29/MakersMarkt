<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

// Public page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard (Breeze)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    // Catalog: iedereen ingelogd
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // Maker-only: product beheren
    Route::middleware('role:maker|admin')->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // Koper-only: orders bekijken
    Route::middleware('role:koper|admin')->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    });

    // Notifications: iedereen ingelogd
    Route::get('/notifications', [NotificationController::class, 'page'])->name('notifications.page');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
