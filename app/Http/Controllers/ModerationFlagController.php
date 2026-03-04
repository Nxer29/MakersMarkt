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
            'flagged_by_user_id' => ['nullable','integer','exists:users,id'],
            'reason' => ['required','string','max:255'],
            'automatic' => ['sometimes','boolean'],
        ]);

        $flag = ModerationFlag::create([
            'product_id' => $productId,
            'flagged_by_user_id' => $data['flagged_by_user_id'] ?? null,
            'reason' => $data['reason'],
            'automatic' => $data['automatic'] ?? false,
            'created_at' => now(),
        ]);

        return response()->json($flag, 201);
    }
}
