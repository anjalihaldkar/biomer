<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class OrderController extends Controller
{
    private function customer()
    {
        return Auth::guard('customer')->user();
    }

    // ── Checkout Page ─────────────────────────────────────────────────
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        $total    = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        $customer = $this->customer();
        return view('checkout', compact('cart', 'total', 'customer'));
    }

    // ── Step 1: Create Razorpay Order (AJAX) ──────────────────────────
    public function createRazorpayOrder(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'email'   => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
            'city'    => 'required|string|max:100',
            'state'   => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'notes'   => 'nullable|string|max:500',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['error' => 'Your cart is empty.'], 422);
        }

        // ✅ Check stock before proceeding
        foreach ($cart as $item) {
            if (!empty($item['variation_id'])) {
                $variation = ProductVariation::find($item['variation_id']);
                if ($variation && $variation->product->manage_stock) {
                    if ($variation->stock_quantity < $item['quantity']) {
                        return response()->json([
                            'error' => "Sorry! '{$item['name']}' only has {$variation->stock_quantity} units in stock."
                        ], 422);
                    }
                }
            } else {
                $product = Product::find($item['product_id']);
                if ($product && $product->manage_stock && $product->variations->count() === 0) {
                    if ($product->stock_quantity < $item['quantity']) {
                        return response()->json([
                            'error' => "Sorry! '{$item['name']}' only has {$product->stock_quantity} units in stock."
                        ], 422);
                    }
                }
            }
        }

        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);

        // Create Razorpay order
        $api = new Api(config('razorpay.key_id'), config('razorpay.key_secret'));

        $razorpayOrder = $api->order->create([
            'receipt'  => 'BB-' . strtoupper(uniqid()),
            'amount'   => (int) round($total * 100), // amount in paise
            'currency' => 'INR',
        ]);

        // Store form data in session for later use
        session()->put('checkout_data', [
            'name'    => $request->name,
            'phone'   => $request->phone,
            'email'   => $request->email,
            'address' => $request->address,
            'city'    => $request->city,
            'state'   => $request->state,
            'pincode' => $request->pincode,
            'notes'   => $request->notes,
        ]);

        $customer = $this->customer();

        return response()->json([
            'success'           => true,
            'razorpay_order_id' => $razorpayOrder->id,
            'amount'            => (int) round($total * 100),
            'currency'          => 'INR',
            'key_id'            => config('razorpay.key_id'),
            'name'              => $customer->name,
            'email'             => $customer->email,
            'phone'             => $customer->phone ?? $request->phone,
        ]);
    }

    // ── Step 2: Payment Success — Verify & Create Order ───────────────
    public function paymentSuccess(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        // Verify Razorpay signature
        $api = new Api(config('razorpay.key_id'), config('razorpay.key_secret'));

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);
        } catch (SignatureVerificationError $e) {
            return response()->json([
                'error' => 'Payment verification failed. Please try again.',
            ], 400);
        }

        // Get stored checkout data
        $checkoutData = session()->get('checkout_data');
        if (!$checkoutData) {
            return response()->json([
                'error' => 'Session expired. Please try again.',
            ], 422);
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['error' => 'Your cart is empty.'], 422);
        }

        $total    = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        $customer = $this->customer();

        $orderNumber = null;

        DB::transaction(function () use ($request, $checkoutData, $cart, $total, $customer, &$orderNumber) {

            $order = Order::create([
                'customer_id'         => $customer->id,
                'order_number'        => 'BB-' . strtoupper(uniqid()),
                'name'                => $checkoutData['name'],
                'phone'               => $checkoutData['phone'],
                'email'               => $checkoutData['email'] ?? $customer->email,
                'address'             => $checkoutData['address'],
                'city'                => $checkoutData['city'],
                'state'               => $checkoutData['state'],
                'pincode'             => $checkoutData['pincode'],
                'notes'               => $checkoutData['notes'],
                'total_amount'        => $total,
                'status'              => 'confirmed',
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'payment_status'      => 'paid',
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'       => $order->id,
                    'product_id'     => $item['product_id'],
                    'variation_id'   => $item['variation_id'] ?? null,
                    'product_name'   => $item['name'],
                    'variation_name' => $item['variation'] ?? null,
                    'sku'            => $item['sku'] ?? null,
                    'unit_price'     => $item['price'],
                    'quantity'       => $item['quantity'],
                    'subtotal'       => $item['price'] * $item['quantity'],
                ]);

                // ✅ Deduct stock after successful payment
                if (!empty($item['variation_id'])) {
                    ProductVariation::where('id', $item['variation_id'])
                        ->decrement('stock_quantity', $item['quantity']);
                } else {
                    Product::where('id', $item['product_id'])
                        ->decrement('stock_quantity', $item['quantity']);
                }
            }

            $orderNumber = $order->order_number;

            session()->forget('cart');
            session()->forget('checkout_data');
        });

        return response()->json([
            'success'      => true,
            'redirect_url' => route('order.success', $orderNumber),
        ]);
    }

    // ── Step 3: Payment Failed (optional handler) ─────────────────────
    public function paymentFailed(Request $request)
    {
        return response()->json([
            'error' => 'Payment was cancelled or failed. No order has been placed.',
        ], 400);
    }

    // ── Order Success ──────────────────────────────────────────────────
    public function success($orderNumber)
    {
        $order = Order::with('items.product')
            ->where('order_number', $orderNumber)
            ->where('customer_id', $this->customer()->id)
            ->firstOrFail();

        return view('order-success', compact('order'));
    }

    // ── My Orders ──────────────────────────────────────────────────────
    public function myOrders()
    {
        $orders = Order::with('items')
            ->where('customer_id', $this->customer()->id)
            ->latest()
            ->paginate(10);

        return view('my-orders', compact('orders'));
    }

    // ── Order Detail ───────────────────────────────────────────────────
    public function show($orderNumber)
    {
        $order = Order::with('items.product')
            ->where('order_number', $orderNumber)
            ->where('customer_id', $this->customer()->id)
            ->firstOrFail();

        return view('order-detail', compact('order'));
    }
}