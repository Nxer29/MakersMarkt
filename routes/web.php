<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard (Breeze)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// App pages (Blade views) for navigation
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/catalog', 'catalog')->name('catalog');
    Route::view('/my-products', 'my-products')->name('my-products');
    Route::view('/my-orders', 'my-orders')->name('my-orders');
    Route::view('/notifications', 'notifications')->name('notifications');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
