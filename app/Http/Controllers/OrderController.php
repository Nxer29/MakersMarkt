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

    public function makerIndex(Request $request)
    {
        $orders = Order::with(['product', 'buyer'])
            ->whereHas('product', function ($q) {
                $q->where('maker_id', auth()->id());
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('maker.orders.index', compact('orders'));
    }

    public function updateStatusAsMaker(Request $request, Order $order)
    {
        $order->load('product');

        // Alleen maker van het product mag dit aanpassen
        if (!$order->product || $order->product->maker_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'status' => ['required','string'],
            'status_note' => ['nullable','string','max:255'],
        ]);

        $allowed = ['nieuw','in_productie','verzonden','geweigerd_terugbetaald'];
        if (!in_array($data['status'], $allowed, true)) {
            return back()->withErrors(['status' => 'Ongeldige status.']);
        }

        if ($data['status'] === 'geweigerd_terugbetaald') {
            // Als al refunded: geen 2e keer
            if ($order->status === 'geweigerd_terugbetaald') {
                return back()->with('success', 'Deze bestelling is al geweigerd en terugbetaald.');
            }

            $refundResult = $this->refund($request, $order, $data['status_note'] ?? null);

            // refund() retourneert een Order (of JSON response bij error). Voor web: success flash.
            if ($refundResult instanceof \Illuminate\Http\JsonResponse) {
                $payload = $refundResult->getData(true);
                return back()->withErrors(['refund' => $payload['message'] ?? 'Refund mislukt.']);
            }

            return back()->with('success', 'Bestelling geweigerd. Terugbetaling verzonden.');
        }

        $order->update([
            'status' => $data['status'],
            'status_note' => $data['status_note'] ?? null,
        ]);

        Notification::create([
            'user_id' => $order->buyer_id,
            'message' => "Bestelling (#{$order->id}) status: {$order->status}.",
            'is_read' => false,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Status bijgewerkt.');
    }

    // POST /orders  (buyer koopt product)
    public function store(Request $request)
    {
        $isJson = $request->wantsJson();

        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,id'],
            'amount' => ['required','numeric','min:0.01'],
        ]);

        $buyer = auth()->user();

        $product = Product::whereNull('deleted_at')
            ->findOrFail($data['product_id']);

        $makerId = $product->maker_id;

        return DB::transaction(function () use ($isJson, $buyer, $product, $makerId, $data) {

            if ((float)$buyer->wallet_credit < (float)$data['amount']) {
                return response()->json(['message' => 'Onvoldoende wallet credit.'], 422);
            }

            $order = Order::create([
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
                'status' => 'nieuw',
                'status_note' => null,
            ]);

            $maker = User::lockForUpdate()->findOrFail($makerId);
            $buyerLocked = User::lockForUpdate()->findOrFail($buyer->id);

            $buyerLocked->wallet_credit -= $data['amount'];
            $maker->wallet_credit += $data['amount'];

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

            Notification::create([
                'user_id' => $maker->id,
                'message' => "Nieuwe bestelling (#{$order->id}) voor product '{$product->name}'.",
                'is_read' => false,
                'created_at' => now(),
            ]);

            if ($isJson) {
                return response()->json($order->load('product'), 201);
            }

            return redirect()->route('orders.index')
                ->with('success', 'Bestelling geplaatst!');
        });
    }

    // PATCH /orders/{order}/status (maker zet status)
    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required','string'],
            'status_note' => ['nullable','string','max:255'],
        ]);

        // ✅ Alleen maker van dit product mag status wijzigen
        $order->loadMissing('product');
        if (($order->product?->maker_id ?? null) !== auth()->id()) {
            abort(403);
        }

        // ✅ Alleen statuses uit US-16 (nieuw is alleen startstatus, niet via update)
        $allowed = ['in_productie','verzonden','geweigerd_terugbetaald'];
        if (!in_array($data['status'], $allowed, true)) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Ongeldige status.'], 422);
            }

            return redirect()
                ->back()
                ->withErrors(['status' => 'Ongeldige status.'])
                ->withInput();
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

        if ($request->wantsJson()) {
            return $order;
        }

        return redirect()
            ->route('maker.orders.index')
            ->with('success', 'Order status bijgewerkt.');
    }

    private function refund(Request $request, Order $order, ?string $note)
    {
        // Idempotency: als refund al bestaat, niet opnieuw boeken
        $alreadyRefunded = CreditTransaction::where('order_id', $order->id)
            ->where('reason', 'refund')
            ->exists();

        if ($alreadyRefunded) {
            $order->update([
                'status' => 'geweigerd_terugbetaald',
                'status_note' => $note,
            ]);

            return $order->fresh();
        }

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

            // Voorkom negatieve maker wallet (als dit niet mag in jouw app)
            if ((float)$maker->wallet_credit < (float)$purchase->amount) {
                return response()->json(['message' => 'Maker heeft onvoldoende krediet om te refunden.'], 422);
            }

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

            return $order->fresh();
        });
    }
}
