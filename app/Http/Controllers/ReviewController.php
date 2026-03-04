<?php

namespace App\Http\Controllers;

use App\Models\{Order, Review, Notification};
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // POST /orders/{order}/review
    public function store(Request $request, Order $order)
    {
        if ($order->status !== 'verzonden') {
            return response()->json(['message' => 'Review kan pas na verzonden status.'], 422);
        }

        if ($order->review) {
            return response()->json(['message' => 'Er bestaat al een review voor deze order.'], 422);
        }

        $data = $request->validate([
            'rating' => ['required','integer','min:1','max:5'],
            'comment' => ['nullable','string'],
        ]);

        $review = Review::create([
            'order_id' => $order->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'created_at' => now(),
        ]);

        // notificatie maker
        $makerId = $order->product->maker_id;
        Notification::create([
            'user_id' => $makerId,
            'message' => "Nieuwe review voor bestelling (#{$order->id}).",
            'is_read' => false,
            'created_at' => now(),
        ]);

        return response()->json($review, 201);
    }
}
