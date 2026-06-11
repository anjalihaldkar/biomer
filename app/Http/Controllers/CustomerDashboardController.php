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

        $totalOrders     = $customer->orders()->count();
        $completedOrders = $customer->orders()->where('status', 'delivered')->count();
        $pendingOrders   = $customer->orders()->whereIn('status', ['pending', 'confirmed', 'processing', 'shipped'])->count();
        $cancelledOrders = $customer->orders()->where('status', 'cancelled')->count();
        $totalWishlist   = $customer->wishlists()->count();
        $recentOrders    = $customer->orders()->latest()->take(5)->get();

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
