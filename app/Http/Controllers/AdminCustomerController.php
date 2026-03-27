<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    // ── Index ──────────────────────────────────────────────────────────
    public function index()
    {
        $customers = Customer::withCount('orders')
            ->withSum('orders', 'total_amount')
            ->latest()
            ->paginate(20);

        return view('dashboard.customers.index', compact('customers'));
    }

    // ── Show ───────────────────────────────────────────────────────────
    public function show($id)
    {
        // ✅ FIXED: $customers -> $customer (single record, not a collection)
        $customer = Customer::with(['orders.items'])->findOrFail($id);

        $orders      = $customer->orders;
        $totalSpent  = $orders->sum('total_amount');
        $totalOrders = $orders->count();

        // ✅ FIXED: compact now passes 'customer' not 'customers'
        return view('dashboard.customers.show', compact('customer', 'orders', 'totalSpent', 'totalOrders'));
    }
}