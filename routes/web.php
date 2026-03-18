<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ModerationSearchController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Product;



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
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/portfolio', [ProductController::class, 'portfolio'])->name('products.portfolio');

    // Orders page (for buyer)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    Route::get('/maker/orders', [OrderController::class, 'makerIndex'])->name('maker.orders.index');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status.update');

    // ✅ Moderation search (US-23) — alleen moderators/admin
    Route::middleware('role:moderator|admin')->group(function () {
        Route::get('/moderation/search', [ModerationSearchController::class, 'index'])->name('moderation.search.index');
    });

    // Notifications page
    Route::get('/notifications', [NotificationController::class, 'page'])->name('notifications.page');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



        Route::get('/cart', function (Request $request) {
        $cart = $request->session()->get('cart', []);
        // ...
        })->name('cart.index');
        Route::get('/cart', function (Request $request) {
            $cart = $request->session()->get('cart', []); // [productId => ['qty'=>1, 'unit_price'=>..]]
            $productIds = array_keys($cart);

            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            $items = [];
            $total = 0;

            foreach ($cart as $productId => $row) {
                $product = $products->get((int)$productId);
                if (!$product) continue;

                $qty = (int)($row['qty'] ?? 1);
                $unit = (float)($row['unit_price'] ?? 0);
                $line = $qty * $unit;

                $items[] = [
                    'product' => $product,
                    'qty' => $qty,
                    'unit_price' => $unit,
                    'line_total' => $line,
                ];

                $total += $line;
            }

            return view('cart.index', compact('items', 'total'));
        })->name('cart.index');

        // Add to cart
        Route::post('/cart/add', function (Request $request) {
            $data = $request->validate([
                'product_id' => ['required','integer','exists:products,id'],
                'unit_price' => ['required','numeric','min:0.01'],
            ]);

            $cart = $request->session()->get('cart', []);

            $pid = (string)$data['product_id'];
            $cart[$pid] = [
                'qty' => (($cart[$pid]['qty'] ?? 0) + 1),
                'unit_price' => (float)$data['unit_price'],
            ];

            $request->session()->put('cart', $cart);

            return back()->with('success', 'Toegevoegd aan winkelwagen.');
        })->name('cart.add');

        // Remove one product entirely
        Route::post('/cart/remove', function (Request $request) {
            $data = $request->validate([
                'product_id' => ['required','integer'],
            ]);

            $cart = $request->session()->get('cart', []);
            unset($cart[(string)$data['product_id']]);
            $request->session()->put('cart', $cart);

            return back()->with('success', 'Verwijderd uit winkelwagen.');
        })->name('cart.remove');
});

require __DIR__.'/auth.php';
