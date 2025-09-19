<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of products with pagination.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Apply category filter
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Apply price range filter
        if ($request->has(['min_price', 'max_price'])) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        // Cache the paginated results
        $cacheKey = 'products.page.' . ($request->page ?? 1);
        $products = Cache::remember($cacheKey, 300, function () use ($query) {
            return $query->with('category')
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        });

        if ($request->wantsJson()) {
            return ProductResource::collection($products);
        }

        return view('products.index', compact('products'));
    }

    /**
     * Display all categories with their product counts.
     */
    public function categories()
    {
        $categories = Cache::remember('categories.with.counts', 600, function () {
            return Category::withCount('products')->get();
        });

        return CategoryResource::collection($categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products',
            'sku' => 'required|string|max:50|unique:products',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string'
        ]);

        $product = Product::create($validated);

        // Clear the products cache
        Cache::tags(['products'])->flush();

        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return response()->json([
            'product' => $product,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'sku' => ['required', 'string', 'max:50', Rule::unique('products')->ignore($product->id)],
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string'
        ]);

        $product->update($validated);

        // Clear the products cache
        Cache::tags(['products'])->flush();

        return response()->json($product);
    }

    /**
     * Search products with pagination.
     */
    public function search(Request $request)
    {
        $query = Product::query();

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sort_by')) {
            $direction = $request->sort_direction === 'desc' ? 'desc' : 'asc';
            $query->orderBy($request->sort_by, $direction);
        }

        $products = $query->paginate(12);

        if ($request->wantsJson()) {
            return ProductResource::collection($products);
        }

        return view('products.index', compact('products'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        // Clear the products cache
        Cache::tags(['products'])->flush();

        return response()->json(null, 204);
    }
}
