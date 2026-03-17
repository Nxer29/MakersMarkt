<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET /products?type=...&q=...
    // GET /products?type=...&material=...&production_time=...&q=...
public function index(Request $request)
{
    $q = Product::query()
        ->whereNull('deleted_at')
        ->where('flagged', false);

    // Filter: type (bestond al)
    if ($request->filled('type')) {
        $q->where('type', $request->string('type'));
    }

    // Filter: material (nieuw)
    // "contains" match zodat "hout" ook matcht op "eikenhout"
    if ($request->filled('material')) {
        $material = '%' . $request->string('material') . '%';
        $q->where('material', 'like', $material);
    }

    // Filter: production_time (nieuw)
    // Omdat production_time een string is, doen we exact match op stored value
    if ($request->filled('production_time')) {
        $q->where('production_time', $request->string('production_time'));
    }

    // Search (bestond al)
    if ($request->filled('q')) {
        $term = '%' . $request->string('q') . '%';
        $q->where(function ($sub) use ($term) {
            $sub->where('name', 'like', $term)
                ->orWhere('description', 'like', $term)
                ->orWhere('material', 'like', $term);
        });
    }

    // maker eager-load (had je al)
    $products = $q->with('maker')
        ->orderByDesc('created_at')
        ->paginate(20)
        ->withQueryString(); // belangrijk: paginatie behoudt filters

    if ($request->wantsJson()) {
        return $products;
    }

    // Dropdown opties (distinct) voor UI filters
    $types = Product::query()
        ->whereNull('deleted_at')
        ->where('flagged', false)
        ->select('type')->distinct()->orderBy('type')->pluck('type');

    $productionTimes = Product::query()
        ->whereNull('deleted_at')
        ->where('flagged', false)
        ->select('production_time')->distinct()->orderBy('production_time')->pluck('production_time');

    return view('products.index', compact('products', 'types', 'productionTimes'));
}

    public function create()
    {
        return view('products.create');
    }

    public function edit(Product $product)
    {
        abort_unless(auth()->check() && $product->maker_id === auth()->id(), 403);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        abort_unless(auth()->check() && $product->maker_id === auth()->id(), 403);

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

        // Als dit ook via web-form gebeurt is redirect vaak fijner dan JSON:
        // return redirect()->route('products.index')->with('success','Product bijgewerkt!');
        return $product;
    }

    public function destroy(Product $product)
    {
        abort_unless(auth()->check() && $product->maker_id === auth()->id(), 403);

        $product->update(['deleted_at' => now()]);
        return response()->noContent();
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

        $product = Product::create($data);

        if ($request->wantsJson()) {
            return response()->json($product->load('maker'), 201);
        }

        return redirect()
            ->route('products.show', $product)
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
