<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET /products?type=...&q=...
    public function index(Request $request)
    {
        $q = Product::query()
            ->whereNull('deleted_at')
            ->where('flagged', false);

        if ($request->filled('type')) {
            $q->where('type', $request->string('type'));
        }

        if ($request->filled('q')) {
            $term = '%' . $request->string('q') . '%';
            $q->where(function ($sub) use ($term) {
                $sub->where('name', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhere('material', 'like', $term);
            });
        }

        // ✅ CHANGE: maker eager-load
        $products = $q->with('maker')->orderByDesc('created_at')->paginate(20);

        if ($request->wantsJson()) {
            return $products;
        }

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    // POST /products (maker)
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => ['required','string'],
            'type' => ['required','string','max:255'],
            'material' => ['required','string'],
            'production_time' => ['required','string','max:255'],
            'complexity' => ['required','string','max:255'],
            'durability' => ['required','string'],
            'unique_features' => ['required','string'],
        ]);

        $data['maker_id'] = auth()->id();


        $data['verified'] = false;

        $product = Product::create($data);

        if ($request->wantsJson()) {
            return response()->json($product->load('maker'), 201);
        }

        return redirect()
            ->route('products.portfolio')
            ->with('success', 'Product toegevoegd!');
    }

    // GET /products/{id}
    public function show(Request $request, Product $product)
    {
        $product->load('maker');

        if ($request->wantsJson()) {
            return $product;
        }

        return view('products.show', compact('product'));
    }

    // PATCH /products/{id}
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['sometimes','string','max:255'],
            'description' => ['sometimes','string'],
            'type' => ['sometimes','string','max:255'],
            'material' => ['sometimes','string'],
            'production_time' => ['sometimes','string','max:255'],
            'complexity' => ['sometimes','string','max:255'],
            'durability' => ['sometimes','string'],
            'unique_features' => ['sometimes','string'],
        ]);

        $product->update($data);

        if ($request->wantsJson()) {
            return $product->load('maker');
        }

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Product bijgewerkt!');
    }


    public function destroy(Request $request, Product $product)
    {
        $product->update(['deleted_at' => now()]);

        if ($request->wantsJson()) {
            return response()->noContent();
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product verwijderd!');
    }
    public function portfolio(Request $request)
    {
        $products = Product::query()
            ->where('maker_id', $request->user()->id)
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('products.portfolio', compact('products'));
    }

}
