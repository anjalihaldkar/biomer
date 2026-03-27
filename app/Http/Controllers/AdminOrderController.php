<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    // ── Index ──────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Order::with('customer')
            ->withCount('items')
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by order number or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        $statusCounts = [
            'all'        => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(),
            'confirmed'  => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped'    => Order::where('status', 'shipped')->count(),
            'delivered'  => Order::where('status', 'delivered')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        return view('dashboard.orders.index', compact('orders', 'statusCounts'));
    }

    // ── Show ───────────────────────────────────────────────────────────
    public function show($orderNumber)
    {
        $order = Order::with(['items.product', 'customer'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('dashboard.orders.show', compact('order'));
    }

    // ── Update Status (AJAX) ───────────────────────────────────────────
    public function updateStatus(Request $request, $orderNumber)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        $order->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'status'  => $order->status,
            'message' => 'Order status updated to ' . ucfirst($order->status),
        ]);
    }
}