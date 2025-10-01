<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(15);
        return ProductResource::collection($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'barcode' => 'nullable|string|unique:products',
            'image' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $product = Product::create($validated);
        return new ProductResource($product->load('category'));
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load('category'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'image' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $product->update($validated);
        return new ProductResource($product->load('category'));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function search($query)
    {
        $products = Product::with('category')
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('barcode', 'LIKE', "%{$query}%")
            ->get();

        return ProductResource::collection($products);
    }

    public function lowStock()
    {
        $products = Product::with('category')
            ->where('stock_quantity', '<', 10)
            ->get();

        return ProductResource::collection($products);
    }
}