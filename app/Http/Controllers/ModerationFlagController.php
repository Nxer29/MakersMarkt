<?php

namespace App\Http\Controllers;

use App\Models\ModerationFlag;
use Illuminate\Http\Request;

class ModerationFlagController extends Controller
{
    // POST /products/{product}/flags
    public function store(Request $request, int $productId)
    {
        $data = $request->validate([
            'reason' => ['required','string','max:255'],
            'automatic' => ['sometimes','boolean'],
        ]);

        $flag = ModerationFlag::create([
            'product_id' => $productId,
            'flagged_by_user_id' => auth()->id(),
            'reason' => $data['reason'],
            'automatic' => $data['automatic'] ?? false,
            'created_at' => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json($flag, 201);
        }

        return back()->with('success', 'Product gemarkeerd voor moderatie.');
    }
}
