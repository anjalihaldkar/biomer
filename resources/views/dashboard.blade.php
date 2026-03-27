@extends('layout.userpanel')
@section('title', 'My Dashboard – Bharat Biomer')

@section('panel')
<style>
.db-cards {
    display: grid; grid-template-columns: repeat(3,1fr);
    gap: 20px; margin-bottom: 30px;
}
.db-card {
    display: flex; align-items: center; gap: 16px;
    padding: 20px; border-radius: 10px;
    transition: transform .2s, box-shadow .2s;
}
.db-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.10); }
.db-card-icon {
    width: 64px; height: 64px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; color: #fff;
}
.db-card-icon svg { width: 32px; height: 32px; }
.db-card-info h3 { font-size: 28px; font-weight: 700; color: #1a1a2e; line-height: 1; margin-bottom: 6px; }
.db-card-info span { font-size: 13px; color: #777; text-transform: capitalize; }

.card-green  { background: #0aa84812; } .card-green  .db-card-icon { background: #0aa848; }
.card-blue   { background: #66aaee1f; } .card-blue   .db-card-icon { background: #66aaee; }
.card-orange { background: #ffa5001c; } .card-orange .db-card-icon { background: #ffa500; }
.card-red    { background: #ff000012; } .card-red    .db-card-icon { background: #e53935; }
.card-purple { background: #80008014; } .card-purple .db-card-icon { background: purple; }

.db-bottom { display: grid; grid-template-columns: 7fr 5fr; gap: 24px; }
.db-panel h3 { font-size: 18px; font-weight: 600; color: #1a1a2e; margin-bottom: 14px; }

.db-orders-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.db-orders-table th { background: #f4faf0; color: #2d7a45; padding: 10px 12px; text-align: left; font-weight: 600; border-bottom: 2px solid #e8f0e4; }
.db-orders-table td { padding: 10px 12px; border-bottom: 1px solid #f0f0f0; color: #444; vertical-align: middle; }
.db-orders-table tr:last-child td { border-bottom: none; }
.db-orders-table tr:hover td { background: #f9fdf7; }

.db-status { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
.db-status--pending    { background:#fff8e1; color:#b45309; }
.db-status--confirmed  { background:#e8f5ed; color:#2d7a45; }
.db-status--processing { background:#e8f5fd; color:#1a6fa8; }
.db-status--shipped    { background:#f3e8fd; color:#6d28d9; }
.db-status--delivered  { background:#e8f5ed; color:#2d7a45; }
.db-status--cancelled  { background:#fdecea; color:#c0392b; }

.db-view-link { color: #2d7a45; font-weight: 600; text-decoration: none; font-size: 13px; }
.db-view-link:hover { text-decoration: underline; }

.db-empty-order { background: #fffbe6; border: 1px solid #ffe58f; border-radius: 8px; padding: 16px 20px; color: #856404; font-size: 14px; }

.db-quick-links { display: flex; flex-direction: column; gap: 10px; }
.db-quick-link {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px; border-radius: 10px;
    border: 1px solid #e8f0e4; text-decoration: none;
    color: #333; font-size: 14px; font-weight: 500; transition: all .2s;
}
.db-quick-link:hover { background: #f4faf0; border-color: #2d7a45; color: #2d7a45; }
.db-quick-link svg { width: 18px; height: 18px; flex-shrink: 0; color: #2d7a45; }

@media (max-width: 1024px) { .db-cards { grid-template-columns: repeat(2,1fr); } .db-bottom { grid-template-columns: 1fr; } }
@media (max-width: 480px)  { .db-cards { grid-template-columns: 1fr; } .db-card-info h3 { font-size: 24px; } }
</style>

{{-- ── Stats Cards ── --}}
<div class="db-cards">

    <div class="db-card card-green">
        <div class="db-card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/></svg>
        </div>
        <div class="db-card-info"><h3>{{ $totalOrders }}</h3><span>Total Orders</span></div>
    </div>

    <div class="db-card card-blue">
        <div class="db-card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z"/></svg>
        </div>
        <div class="db-card-info"><h3>{{ $completedOrders }}</h3><span>Completed Orders</span></div>
    </div>

    <div class="db-card card-orange">
        <div class="db-card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
        </div>
        <div class="db-card-info"><h3>{{ $pendingOrders }}</h3><span>Pending Orders</span></div>
    </div>

    <div class="db-card card-red">
        <div class="db-card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
        </div>
        <div class="db-card-info"><h3>{{ $cancelledOrders }}</h3><span>Cancelled Orders</span></div>
    </div>

    <div class="db-card card-purple">
        <div class="db-card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
        </div>
        <div class="db-card-info"><h3>{{ $totalWishlist }}</h3><span>Total Wishlist</span></div>
    </div>

</div>

{{-- ── Bottom Section ── --}}
<div class="db-bottom">

    {{-- Recent Orders --}}
    <div class="db-panel">
        <h3>Recent Orders</h3>
        @if($recentOrders->count() > 0)
        <div style="overflow-x:auto;">
            <table class="db-orders-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td><strong>#{{ $order->order_number }}</strong></td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td><strong>₹{{ number_format($order->total_amount, 2) }}</strong></td>
                        <td>
                            <span class="db-status db-status--{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('orders.show', $order->order_number) }}" class="db-view-link">View →</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">
            <a href="{{ route('orders.index') }}" class="db-view-link">View all orders →</a>
        </div>
        @else
        <div class="db-empty-order">No recent orders found.</div>
        @endif
    </div>

    {{-- Quick Links --}}
    <div class="db-panel">
        <h3>Quick Links</h3>
        <div class="db-quick-links">
            <a href="{{ route('orders.index') }}" class="db-quick-link">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z"/></svg>
                My Orders
            </a>
            <a href="{{ route('wishlist.index') }}" class="db-quick-link">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                My Wishlist
                @if($totalWishlist > 0)
                    <span style="margin-left:auto; background:#2d7a45; color:#fff; font-size:11px; font-weight:700; padding:2px 8px; border-radius:20px;">{{ $totalWishlist }}</span>
                @endif
            </a>
            <a href="{{ route('products.index') }}" class="db-quick-link">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016 2.993 2.993 0 0 0 2.25-1.016 3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h10.5"/></svg>
                Browse Products
            </a>
            <a href="{{ route('cart.index') }}" class="db-quick-link">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/></svg>
                My Cart
            </a>
        </div>
    </div>

</div>

@endsection