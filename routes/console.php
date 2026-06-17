<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\StockReservation;
use App\Models\User;
use App\Models\Order;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('admin:promote {email} {role=admin : admin, super-admin, or user}', function (string $email, string $role) {
    if (!in_array($role, ['user', 'admin', 'super-admin'], true)) {
        $this->error('Role must be one of: user, admin, super-admin.');
        return 1;
    }

    $user = User::where('email', $email)->first();

    if (!$user) {
        $this->error("No user found with email {$email}.");
        return 1;
    }

    $user->forceFill(['role' => $role])->save();
    $this->info("Updated {$email} role to {$role}.");

    return 0;
})->purpose('Set an admin panel user role without making role mass assignable.');

Artisan::command('stock-reservations:release-expired', function () {
    $tokens = StockReservation::where('status', 'active')
        ->where('expires_at', '<=', now())
        ->distinct()
        ->pluck('token');

    foreach ($tokens as $token) {
        DB::transaction(function () use ($token) {
            $reservations = StockReservation::where('token', $token)
                ->where('status', 'active')
                ->lockForUpdate()
                ->get();

            foreach ($reservations as $reservation) {
                if ($reservation->variation_id) {
                    ProductVariation::where('id', $reservation->variation_id)
                        ->increment('stock_quantity', $reservation->quantity);
                } else {
                    Product::where('id', $reservation->product_id)
                        ->increment('stock_quantity', $reservation->quantity);
                }
            }

            StockReservation::whereIn('id', $reservations->pluck('id'))
                ->update(['status' => 'released']);
        });
    }

    $this->info("Released {$tokens->count()} expired stock reservation(s).");

    return 0;
})->purpose('Release expired checkout stock reservations.');

Schedule::command('stock-reservations:release-expired')->everyFiveMinutes();

Artisan::command('orders:cancel-expired-pending-payments', function () {
    $orders = Order::with('items')
        ->whereIn('payment_gateway', ['razorpay', 'cashfree'])
        ->where('payment_status', 'pending')
        ->where('created_at', '<=', now()->subMinutes(45))
        ->get();

    foreach ($orders as $order) {
        DB::transaction(function () use ($order) {
            $lockedOrder = Order::with('items')->whereKey($order->id)->lockForUpdate()->first();

            if (!$lockedOrder || $lockedOrder->payment_status !== 'pending') {
                return;
            }

            foreach ($lockedOrder->items as $item) {
                if ($item->variation_id) {
                    $variation = ProductVariation::with('product')->find($item->variation_id);
                    if ($variation && $variation->product?->manage_stock) {
                        $variation->increment('stock_quantity', $item->quantity);
                    }
                    continue;
                }

                $product = Product::withCount('variations')->find($item->product_id);
                if ($product && $product->manage_stock && $product->variations_count === 0) {
                    $product->increment('stock_quantity', $item->quantity);
                }
            }

            $lockedOrder->update([
                'status' => 'cancelled',
                'payment_status' => 'failed',
            ]);
        });
    }

    $this->info("Cancelled {$orders->count()} expired pending payment order(s).");

    return 0;
})->purpose('Cancel stale pending Razorpay/Cashfree orders and restore held stock.');

Schedule::command('orders:cancel-expired-pending-payments')->everyTenMinutes();
