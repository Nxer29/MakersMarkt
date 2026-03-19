<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProductController,
    OrderController,
    ReviewController,
    NotificationController,
    ModerationFlagController
};

Route::middleware(['auth:sanctum'])->group(function () {

    // Iedereen ingelogd: catalog
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    // Maker-only: products beheren + order status
    Route::middleware('role:maker|admin')->group(function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::patch('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);

        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);

    });

    // Koper-only: orders plaatsen + review
    Route::middleware('role:koper|admin')->group(function () {
        Route::post('/orders', [OrderController::class, 'store']);
        Route::post('/orders/{order}/review', [ReviewController::class, 'store']);
    });

    // Iedereen ingelogd: notifications + flags
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markRead']);
    Route::post('/products/{productId}/flags', [ModerationFlagController::class, 'store']);
});
