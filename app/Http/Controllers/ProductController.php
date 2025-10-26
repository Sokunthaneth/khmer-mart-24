<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Apply category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Apply search filter with proper grouping
        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            if ($searchTerm !== '') {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%'.$searchTerm.'%')
                        ->orWhere('description', 'like', '%'.$searchTerm.'%')
                        ->orWhere('summary', 'like', '%'.$searchTerm.'%');
                });
            }
        }

        // Get products with category and productSkus relationships and paginate
        $products = $query->with(['category', 'productSkus'])->paginate(12)->appends($request->query());

        // Cache categories with product counts for 5 minutes (300 seconds)
        $categories = Cache::remember('categories_with_counts', 300, function() {
            return Category::withCount('products')->get();
        });

        // If request wants JSON (API), return JSON
        if ($request->expectsJson()) {
            return response()->json($products);
        }

        // Otherwise return view
        return view('products.index', compact('products', 'categories'));
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
            'description' => 'nullable|string',
            'summary' => 'nullable|string',
            'cover' => 'nullable|string|url',
        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product, Request $request)
    {
        $product->load(['category', 'productSkus']);

        // If request wants JSON (API), return JSON
        if ($request->expectsJson()) {
            return response()->json($product);
        }

        // Otherwise return view
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();

        return response()->json([
            'product' => $product,
            'categories' => $categories,
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
            'description' => 'nullable|string',
            'summary' => 'nullable|string',
            'cover' => 'nullable|string|url',
        ]);

        $product->update($validated);

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }
}
