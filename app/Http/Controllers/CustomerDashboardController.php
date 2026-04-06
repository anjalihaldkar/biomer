<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Customer;    // ✅ add this
use App\Models\Order;       // ✅ add this
use App\Models\Wishlist;    // ✅ add this

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        $orders          = $customer->orders()->latest()->get();
        $totalOrders     = $orders->count();
        $completedOrders = $orders->where('status', 'delivered')->count();
        $pendingOrders   = $orders->whereIn('status', ['pending', 'confirmed', 'processing', 'shipped'])->count();
        $cancelledOrders = $orders->where('status', 'cancelled')->count();
        $totalWishlist   = $customer->wishlists()->count();
        $recentOrders    = $orders->take(5);

        return view('dashboard', compact(
            'customer',
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'cancelledOrders',
            'totalWishlist',
            'recentOrders'
        ));
    }

    public function account()
    {
        $customer = Auth::guard('customer')->user();
        return view('my-account', compact('customer'));
    }

    public function edit()
    {
        $customer = Auth::guard('customer')->user();
        return view('customer-account-edit', compact('customer'));
    }

    public function update(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update($validated);

        return redirect()->route('customer.account')->with('success', 'Account details updated successfully!');
    }
}