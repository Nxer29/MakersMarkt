<?php

namespace App\Http\Controllers;

use App\Models\{Order, Product, CreditTransaction, Notification, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('product')
            ->where('buyer_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(10);

        if ($request->wantsJson()) {
            return $orders;
        }

        return view('orders.index', compact('orders'));
    }

    // POST /orders  (buyer koopt product)
    // body: buyer_id, product_id, amount (prijs)
    public function store(Request $request)
    {
        $isJson = $request->wantsJson();

        $data = $request->validate([
            'buyer_id' => ['required','integer','exists:users,id'],
            'product_id' => ['required','integer','exists:products,id'],
            'amount' => ['required','numeric','min:0.01'],
        ]);

        $buyer = User::findOrFail($data['buyer_id']);
        $product = Product::whereNull('deleted_at')->findOrFail($data['product_id']);
        $makerId = $product->maker_id;

        return DB::transaction(function () use ($isJson, $buyer, $product, $makerId, $data) {
            // saldo check
            if ((float)$buyer->wallet_credit < (float)$data['amount']) {
                return response()->json(['message' => 'Onvoldoende wallet credit.'], 422);
            }

            // order maken
            $order = Order::create([
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
                'status' => 'nieuw',
                'status_note' => null,
            ]);

            // wallet: koper -> maker
            $maker = User::lockForUpdate()->findOrFail($makerId);
            $buyerLocked = User::lockForUpdate()->findOrFail($buyer->id);

            $buyerLocked->wallet_credit = (float)$buyerLocked->wallet_credit - (float)$data['amount'];
            $maker->wallet_credit = (float)$maker->wallet_credit + (float)$data['amount'];

            $buyerLocked->save();
            $maker->save();

            CreditTransaction::create([
                'from_user_id' => $buyerLocked->id,
                'to_user_id' => $maker->id,
                'order_id' => $order->id,
                'amount' => $data['amount'],
                'reason' => 'purchase',
                'created_at' => now(),
            ]);

            // notificatie voor maker
            Notification::create([
                'user_id' => $maker->id,
                'message' => "Nieuwe bestelling (#{$order->id}) voor product '{$product->name}'.",
                'is_read' => false,
                'created_at' => now(),
            ]);

            if ($isJson) {
                return response()->json($order->load('product'), 201);
            }

            return redirect()->route('orders.index')->with('success', 'Bestelling geplaatst!');
        });
    }

    // PATCH /orders/{order}/status (maker zet status)
    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required','string'],
            'status_note' => ['nullable','string','max:255'],
        ]);

        $allowed = ['nieuw','in_productie','verzonden','geweigerd_terugbetaald'];
        if (!in_array($data['status'], $allowed, true)) {
            return response()->json(['message' => 'Ongeldige status.'], 422);
        }

        // Als geweigerd_terugbetaald => refund transactie uitvoeren
        if ($data['status'] === 'geweigerd_terugbetaald') {
            return $this->refund($request, $order, $data['status_note'] ?? null);
        }

        $order->update([
            'status' => $data['status'],
            'status_note' => $data['status_note'] ?? null,
        ]);

        // notificatie buyer
        Notification::create([
            'user_id' => $order->buyer_id,
            'message' => "Bestelling (#{$order->id}) status: {$order->status}.",
            'is_read' => false,
            'created_at' => now(),
        ]);

        return $order;
    }

    private function refund(Request $request, Order $order, ?string $note)
    {
        $purchase = CreditTransaction::where('order_id', $order->id)
            ->where('reason', 'purchase')
            ->orderByDesc('id')
            ->first();

        if (!$purchase) {
            return response()->json(['message' => 'Geen purchase transactie gevonden voor refund.'], 422);
        }

        return DB::transaction(function () use ($order, $purchase, $note) {
            $maker = User::lockForUpdate()->findOrFail($purchase->to_user_id);
            $buyer = User::lockForUpdate()->findOrFail($purchase->from_user_id);

            $maker->wallet_credit = (float)$maker->wallet_credit - (float)$purchase->amount;
            $buyer->wallet_credit = (float)$buyer->wallet_credit + (float)$purchase->amount;
            $maker->save();
            $buyer->save();

            CreditTransaction::create([
                'from_user_id' => $maker->id,
                'to_user_id' => $buyer->id,
                'order_id' => $order->id,
                'amount' => $purchase->amount,
                'reason' => 'refund',
                'created_at' => now(),
            ]);

            $order->update([
                'status' => 'geweigerd_terugbetaald',
                'status_note' => $note,
            ]);

            Notification::create([
                'user_id' => $buyer->id,
                'message' => "Bestelling (#{$order->id}) geweigerd. Terugbetaling verzonden.",
                'is_read' => false,
                'created_at' => now(),
            ]);

            return $order;
        });
    }
}
