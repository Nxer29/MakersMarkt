<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ModerationSearchController;
use App\Http\Controllers\ModerationUserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Notification;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserVerificationController;

// Public page
Route::get('/', function () {
    return view('home');
})->name('home');

// Dashboard (Breeze)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin dashboard
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::get('/users', [UserVerificationController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/verified', [UserVerificationController::class, 'update'])->name('users.verified');
    });

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

    // Maker: incoming orders
    Route::middleware(['role:maker|admin'])->group(function () {
        Route::get('/maker/orders', [OrderController::class, 'makerIndex'])->name('maker.orders.index');
        Route::patch('/maker/orders/{order}/status', [OrderController::class, 'updateStatusAsMaker'])->name('maker.orders.status');
    });

    // (als je deze 2 echt nog gebruikt, laat ze staan)
    Route::get('/maker/orders', [OrderController::class, 'makerIndex'])->name('maker.orders.index');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status.update');

    // ✅ Moderation pages (US-20 + US-23) — alleen admin
    Route::middleware('role:admin')->group(function () {
        // US-20: users verify
        Route::get('/moderation/users', [ModerationUserController::class, 'index'])
            ->name('moderation.users.index');

        Route::patch('/moderation/users/{user}/verify', [ModerationUserController::class, 'updateVerified'])
            ->name('moderation.users.verify');

        // US-23: search in texts
        Route::get('/moderation/search', [ModerationSearchController::class, 'index'])
            ->name('moderation.search.index');
    });

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'page'])->name('notifications.page');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markRead'])
        ->name('notifications.read');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart
    Route::get('/cart', function (Request $request) {
        $cart = $request->session()->get('cart', []); // [productId => qty]
        $productIds = array_keys($cart);

        $products = Product::whereIn('id', $productIds)
            ->whereNull('deleted_at')
            ->get()
            ->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $qty) {
            $product = $products->get((int)$productId);
            if (!$product) continue;

            $qty = (int)$qty;
            $unit = (float)$product->price;
            $line = $qty * $unit;

            $items[] = compact('product', 'qty', 'unit') + ['line' => $line];
            $total += $line;
        }

        return view('cart.index', compact('items', 'total'));
    })->name('cart.index');

    // add to cart
    Route::post('/cart/add', function (Request $request) {
        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,id'],
        ]);

        $cart = $request->session()->get('cart', []);
        $pid = (string)$data['product_id'];

        $cart[$pid] = (int)(($cart[$pid] ?? 0) + 1);

        $request->session()->put('cart', $cart);

        return back()->with('success', 'Toegevoegd aan winkelwagen.');
    })->name('cart.add');

    // remove product line
    Route::post('/cart/remove', function (Request $request) {
        $data = $request->validate([
            'product_id' => ['required','integer'],
        ]);

        $cart = $request->session()->get('cart', []);
        unset($cart[(string)$data['product_id']]);
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Verwijderd uit winkelwagen.');
    })->name('cart.remove');

    // checkout
    Route::post('/cart/checkout', function (Request $request) {
        $buyer = $request->user();

        return DB::transaction(function () use ($request, $buyer) {

            $cart = $request->session()->get('cart', []);
            if (empty($cart)) {
                return back()->with('error', 'Je winkelwagen is leeg.');
            }

            $productIds = array_keys($cart);
            $products = Product::whereIn('id', $productIds)
                ->whereNull('deleted_at')
                ->get()
                ->keyBy('id');

            // totaal berekenen
            $total = 0;
            foreach ($cart as $productId => $qty) {
                $product = $products->get((int)$productId);
                if (!$product) continue;
                $total += (int)$qty * (float)$product->price;
            }

            // lock buyer + krediet check
            $buyerLocked = User::lockForUpdate()->findOrFail($buyer->id);

            if ((float)$buyerLocked->wallet_credit < (float)$total) {
                return back()->with('error', 'Onvoldoende winkelkrediet.');
            }

            // per product orders maken + direct transfer + log
            foreach ($cart as $productId => $qty) {
                $product = $products->get((int)$productId);
                if (!$product) continue;

                $maker = User::lockForUpdate()->findOrFail($product->maker_id);

                $qty = (int)$qty;
                $amount = $qty * (float)$product->price;

                $order = Order::create([
                    'buyer_id' => $buyerLocked->id,
                    'product_id' => $product->id,
                    'status' => 'nieuw',
                    'status_note' => null,
                ]);

                // transfer koper -> maker
                $buyerLocked->wallet_credit = (float)$buyerLocked->wallet_credit - (float)$amount;
                $maker->wallet_credit = (float)$maker->wallet_credit + (float)$amount;
                $buyerLocked->save();
                $maker->save();

                // log
                CreditTransaction::create([
                    'from_user_id' => $buyerLocked->id,
                    'to_user_id' => $maker->id,
                    'order_id' => $order->id,
                    'amount' => $amount,
                    'reason' => 'purchase',
                    'created_at' => now(),
                ]);

                Notification::create([
                    'user_id' => $maker->id,
                    'message' => "Nieuwe bestelling (#{$order->id}) voor product '{$product->name}'.",
                    'is_read' => false,
                    'created_at' => now(),
                ]);
            }

            // cart legen
            $request->session()->forget('cart');

            return redirect()->route('orders.index')->with('success', 'Bestelling geplaatst!');
        });
    })->name('cart.checkout');

    // deposit credit
    Route::post('/profile/deposit-credit', function (Request $request) {
        $data = $request->validate([
            'amount' => ['required','numeric','min:0.01'],
        ]);

        return DB::transaction(function () use ($request, $data) {
            $user = User::lockForUpdate()->findOrFail($request->user()->id);

            $user->wallet_credit = (float)$user->wallet_credit + (float)$data['amount'];
            $user->save();

            CreditTransaction::create([
                'from_user_id' => $user->id,
                'to_user_id' => $user->id,
                'order_id' => null,
                'amount' => $data['amount'],
                'reason' => 'deposit',
                'created_at' => now(),
            ]);

            return back()->with('success', 'Krediet gestort!');
        });
    })->name('profile.deposit-credit');
});

require __DIR__.'/auth.php';
