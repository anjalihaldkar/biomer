<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
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
}