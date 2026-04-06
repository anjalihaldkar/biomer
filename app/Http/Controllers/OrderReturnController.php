<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderReturnController extends Controller
{
    public function index()
    {
        $returns = OrderReturn::where('customer_id', Auth::guard('customer')->id())
            ->with(['order'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('order-returns', compact('returns'));
    }

    public function create($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('customer_id', Auth::guard('customer')->id())
            ->with(['orderItems.product'])
            ->firstOrFail();

        // Only allow returns for delivered orders within 30 days
        if ($order->status !== 'delivered' || $order->delivered_at && $order->delivered_at->diffInDays(now()) > 30) {
            abort(403, 'Returns are only allowed for delivered orders within 30 days.');
        }

        return view('order-return-create', compact('order'));
    }

    public function store(Request $request, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('customer_id', Auth::guard('customer')->id())
            ->firstOrFail();

        // Validate return request
        if ($order->status !== 'delivered' || $order->delivered_at && $order->delivered_at->diffInDays(now()) > 30) {
            return back()->withErrors(['error' => 'Returns are only allowed for delivered orders within 30 days.']);
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|in:defective,wrong_item,not_as_described,damaged,other',
            'description' => 'required|string|max:500',
            'refund_amount' => 'required|numeric|min:0|max:' . $order->total_amount,
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if return already exists for this order
        $existingReturn = OrderReturn::where('order_id', $order->id)->first();
        if ($existingReturn) {
            return back()->withErrors(['error' => 'A return request already exists for this order.']);
        }

        OrderReturn::create([
            'order_id' => $order->id,
            'customer_id' => Auth::guard('customer')->id(),
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'pending',
            'refund_amount' => $request->refund_amount,
        ]);

        return redirect()->route('order-returns.index')->with('success', 'Return request submitted successfully. We will review it shortly.');
    }

    public function show($id)
    {
        $return = OrderReturn::where('id', $id)
            ->where('customer_id', Auth::guard('customer')->id())
            ->with(['order.orderItems.product'])
            ->firstOrFail();

        return view('order-return-show', compact('return'));
    }
}