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
        
        $discount = 0;
        $coupon = session()->get('coupon');
        if ($coupon) {
            $discount = $coupon['type'] === 'percent' ? ($total * ($coupon['value'] / 100)) : $coupon['value'];
        }
        
        $finalTotal = max(0, $total - $discount);

        return view('cart', compact('cart', 'total', 'discount', 'coupon', 'finalTotal'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Cart is empty.']);
        }

        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);

        $coupon = \App\Models\Coupon::where('code', $request->code)->where('is_active', true)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid or inactive coupon.']);
        }

        if ($coupon->expires_at && \Carbon\Carbon::parse($coupon->expires_at)->isPast()) {
            return response()->json(['success' => false, 'message' => 'This coupon has expired.']);
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json(['success' => false, 'message' => 'This coupon usage limit has been reached.']);
        }

        if ($total < $coupon->min_order_amount) {
            return response()->json(['success' => false, 'message' => 'Minimum order amount for this coupon is ₹' . number_format($coupon->min_order_amount, 2)]);
        }

        $discount = $coupon->type === 'percent' ? ($total * ($coupon->value / 100)) : $coupon->value;

        session()->put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'discount' => $discount
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => '₹' . number_format($discount, 2),
            'final_total' => '₹' . number_format(max(0, $total - $discount), 2)
        ]);
    }

    public function removeCoupon(Request $request)
    {
        session()->forget('coupon');
        
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        
        return response()->json([
            'success' => true,
            'message' => 'Coupon removed.',
            'original_total' => '₹' . number_format($total, 2)
        ]);
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
