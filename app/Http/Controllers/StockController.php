<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('variations')->get();

        $stocks = [];

        foreach ($products as $product) {
            if ($product->variations->count() > 0) {
                // Track per variation
                foreach ($product->variations as $variation) {
                    $stock = $variation->stock_quantity;

                    // Apply filter
                    if ($request->filter === 'low' && !($stock <= 5 && $stock > 0)) continue;
                    if ($request->filter === 'out' && $stock !== 0) continue;

                    $stocks[] = [
                        'id'             => $variation->id,
                        'type'           => 'variation',
                        'product_name'   => $product->name,
                        'sku'            => $variation->sku,
                        'variation_name' => $variation->attribute_value,
                        'stock'          => $stock,
                    ];
                }
            } else {
                // Track per product
                $stock = $product->stock_quantity;

                if ($request->filter === 'low' && !($stock <= 5 && $stock > 0)) continue;
                if ($request->filter === 'out' && $stock !== 0) continue;

                $stocks[] = [
                    'id'             => $product->id,
                    'type'           => 'product',
                    'product_name'   => $product->name,
                    'sku'            => $product->sku,
                    'variation_name' => null,
                    'stock'          => $stock,
                ];
            }
        }

        $allProducts  = Product::with('variations')->get();
        $totalProducts = $allProducts->count();
        $inStock      = 0;
        $lowStock     = 0;
        $outOfStock   = 0;

        foreach ($allProducts as $p) {
            $p->load('variations');
            if ($p->isInStock()) {
                $p->isLowStock() ? $lowStock++ : $inStock++;
            } else {
                $outOfStock++;
            }
        }

        return view('dashboard.stock.index', compact(
            'stocks', 'totalProducts', 'inStock', 'lowStock', 'outOfStock'
        ));
    }

    // ── Edit Stock ─────────────────────────────────────────────────────
    public function edit(Request $request)
    {
        $type = $request->type;
        $id   = $request->id;

        $item = $type === 'variation'
            ? ProductVariation::with('product')->findOrFail($id)
            : Product::findOrFail($id);

        return view('dashboard.stock.edit', compact('item', 'type'));
    }

    // ── Update Stock ───────────────────────────────────────────────────
    public function update(Request $request)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            'type'           => 'required|in:product,variation',
            'id'             => 'required|integer',
        ]);

        if ($request->type === 'variation') {
            ProductVariation::where('id', $request->id)
                ->update(['stock_quantity' => $request->stock_quantity]);
        } else {
            Product::where('id', $request->id)
                ->update(['stock_quantity' => $request->stock_quantity]);
        }

        return redirect()->route('dashboard.stock.index')
            ->with('success', 'Stock updated successfully!');
    }
}