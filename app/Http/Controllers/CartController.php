<?php
// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart  = session()->get('cart', []);
        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        return view('cart', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id'   => 'required|exists:products,id',
            'variation_id' => 'nullable|exists:product_variations,id',
            'quantity'     => 'nullable|integer|min:1',
        ]);

        $product   = Product::findOrFail($request->product_id);
        $variation = $request->variation_id
            ? ProductVariation::findOrFail($request->variation_id)
            : null;

        $price = $variation ? $variation->price : $product->base_price;
        $key   = $product->id . ':' . ($variation ? $variation->id : '0');
        $cart  = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $request->quantity ?? 1;
        } else {
            $cart[$key] = [
                'product_id'   => $product->id,
                'variation_id' => $variation?->id,
                'name'         => $product->name,
                'variation'    => $variation?->attribute_value ?? null,
                'sku'          => $variation?->sku ?? $product->sku,
                'price'        => $price,
                'quantity'     => $request->quantity ?? 1,
                'image'        => $variation?->image_path ?? $product->featured_image,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success'    => true,
            'message'    => $product->name . ' added to cart!',
            'cart_count' => collect($cart)->sum('quantity'),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'key'      => 'required|string',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->key])) {
            $cart[$request->key]['quantity'] = $request->quantity;
            session()->put('cart', $cart);

            $item      = $cart[$request->key];
            $itemTotal = $item['price'] * $item['quantity'];
            $cartTotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);

            return response()->json([
                'success'    => true,
                'item_total' => '₹' . number_format($itemTotal, 2),
                'cart_total' => '₹' . number_format($cartTotal, 2),
                'cart_count' => collect($cart)->sum('quantity'),
            ]);
        }

        return response()->json(['success' => false], 404);
    }

    public function remove(Request $request)
    {
        $request->validate(['key' => 'required|string']);
        $cart = session()->get('cart', []);
        unset($cart[$request->key]);
        session()->put('cart', $cart);
        $cartTotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        return response()->json([
            'success'    => true,
            'cart_total' => '₹' . number_format($cartTotal, 2),
            'cart_count' => collect($cart)->sum('quantity'),
            'empty'      => count($cart) === 0,
        ]);
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }
}
