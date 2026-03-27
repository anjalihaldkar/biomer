<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductVariationController extends Controller
{
    public function index(Product $product)
    {
        $variations = $product->variations()->latest()->get();
        return view('dashboard.variations.index', compact('product', 'variations'));
    }

    public function create(Product $product)
    {
        return view('dashboard.variations.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'sku'             => 'required|string|max:100|unique:product_variations,sku',
            'attribute_name'  => 'required|string|max:100',
            'attribute_value' => 'required|string|max:100',
            'price'           => 'required|numeric|min:0',
            'weight'          => 'nullable|numeric|min:0',
            'stock_quantity'  => 'required|integer|min:0',
            'image'           => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products/variations', 'public');
        }

        $product->variations()->create([
            'sku'             => $request->sku,
            'attribute_name'  => $request->attribute_name,
            'attribute_value' => $request->attribute_value,
            'price'           => $request->price,
            'weight'          => $request->weight,
            'stock_quantity'  => $request->stock_quantity,
            'is_active'       => $request->boolean('is_active', true),
            'image_path'      => $imagePath,
        ]);

        return redirect()->route('dashboard.products.variations.index', $product)
            ->with('success', 'Variation added successfully!');
    }

    public function edit(Product $product, ProductVariation $variation)
    {
        return view('dashboard.variations.edit', compact('product', 'variation'));
    }

    public function update(Request $request, Product $product, ProductVariation $variation)
    {
        $request->validate([
            'sku'             => 'required|string|max:100|unique:product_variations,sku,' . $variation->id,
            'attribute_name'  => 'required|string|max:100',
            'attribute_value' => 'required|string|max:100',
            'price'           => 'required|numeric|min:0',
            'weight'          => 'nullable|numeric|min:0',
            'stock_quantity'  => 'required|integer|min:0',
            'image'           => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($variation->image_path) Storage::disk('public')->delete($variation->image_path);
            $variation->image_path = $request->file('image')->store('products/variations', 'public');
        }

        $variation->update([
            'sku'             => $request->sku,
            'attribute_name'  => $request->attribute_name,
            'attribute_value' => $request->attribute_value,
            'price'           => $request->price,
            'weight'          => $request->weight,
            'stock_quantity'  => $request->stock_quantity,
            'is_active'       => $request->boolean('is_active', true),
            'image_path'      => $variation->image_path,
        ]);

        return redirect()->route('dashboard.products.variations.index', $product)
            ->with('success', 'Variation updated!');
    }

    public function destroy(Product $product, ProductVariation $variation)
    {
        if ($variation->image_path) Storage::disk('public')->delete($variation->image_path);
        $variation->delete();
        return redirect()->route('dashboard.products.variations.index', $product)
            ->with('success', 'Variation deleted.');
    }

    // AJAX – toggle active status
    public function toggleStatus(ProductVariation $variation)
    {
        $variation->update(['is_active' => !$variation->is_active]);
        return response()->json(['is_active' => $variation->is_active]);
    }
}