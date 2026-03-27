{{-- resources/views/dashboard/customers/show.blade.php --}}
@extends('layout.layout')

@php
    $title    = 'Customer Detail';
    $subTitle = 'Customer Detail';
@endphp

@section('content')

{{-- Customer Info Card --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Customer Details</h5>
        <a href="{{ route('dashboard.customers.index') }}" class="btn btn-sm btn-secondary">← Back</a>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <p class="mb-1 text-muted text-sm">Full Name</p>
                <h6 class="fw-semibold">{{ $customer->name }}</h6>
            </div>
            <div class="col-md-6">
                <p class="mb-1 text-muted text-sm">Email</p>
                <h6 class="fw-semibold">{{ $customer->email }}</h6>
            </div>
            <div class="col-md-6">
                <p class="mb-1 text-muted text-sm">Phone</p>
                <h6 class="fw-semibold">{{ $customer->phone ?? '—' }}</h6>
            </div>
            <div class="col-md-6">
                <p class="mb-1 text-muted text-sm">Joined</p>
                <h6 class="fw-semibold">{{ $customer->created_at?->format('d M Y') ?? '—' }}</h6>
            </div>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card text-center py-3">
            <h6 class="text-muted mb-1">Total Orders</h6>
            <h3 class="fw-bold text-primary">{{ $totalOrders }}</h3>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-center py-3">
            <h6 class="text-muted mb-1">Total Spent</h6>
            <h3 class="fw-bold text-success">₹{{ number_format($totalSpent, 2) }}</h3>
        </div>
    </div>
</div>

{{-- Orders Table --}}
<div class="card basic-data-table">
    <div class="card-header">
        <h5 class="card-title mb-0">Order History</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>S.L</th>
                        <th>Order Number</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $i => $order)
                    <tr>
                        <td>{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td>
                        <td>#{{ $order->order_number ?? $order->id }}</td>
                        <td>{{ $order->items->count() }} item(s)</td>
                        <td>
                            <span class="fw-semibold text-success-main">
                                ₹{{ number_format($order->total_amount, 2) }}
                            </span>
                        </td>
                        <td>
                            <span class="bg-primary-focus text-primary-600 px-12 py-4 rounded-pill fw-medium text-sm">
                                {{ ucfirst($order->status ?? 'N/A') }}
                            </span>
                        </td>
                        <td>{{ $order->created_at?->format('d M Y') ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">No orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection