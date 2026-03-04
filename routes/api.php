<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProductController,
    OrderController,
    ReviewController,
    NotificationController,
    ModerationFlagController
};

// If you want these to require login via Sanctum, use ->middleware('auth:sanctum')
// For now (prototype) I'll leave them open or you can enable auth:sanctum later.

Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::patch('/products/{product}', [ProductController::class, 'update']);
Route::delete('/products/{product}', [ProductController::class, 'destroy']);

Route::post('/orders', [OrderController::class, 'store']);
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);

Route::post('/orders/{order}/review', [ReviewController::class, 'store']);

Route::get('/users/{userId}/notifications', [NotificationController::class, 'index']);
Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markRead']);

Route::post('/products/{productId}/flags', [ModerationFlagController::class, 'store']);
