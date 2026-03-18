<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ModerationSearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $productResults = collect();
        $reviewResults = collect();

        if ($q !== '') {
            // ✅ Products: zoek in description (minimaal)
            $productResults = Product::query()
                ->select(['id', 'name', 'description'])
                ->whereNotNull('description')
                ->where('description', 'like', "%{$q}%")
                ->orderByDesc('id')
                ->limit(50)
                ->get();

            // ✅ Reviews: zoek in comment + context via order->product en order->buyer
            $reviewResults = Review::query()
                ->with([
                    'order' => function ($q2) {
                        $q2->select(['id', 'product_id', 'buyer_id'])
                            ->with([
                                'product:id,name',
                                'buyer:id,name',
                            ]);
                    },
                ])
                ->whereNotNull('comment')
                ->where('comment', 'like', "%{$q}%")
                ->orderByDesc('id')
                ->limit(50)
                ->get();
        }

        return view('moderation.search.index', compact('q', 'productResults', 'reviewResults'));
    }
}
