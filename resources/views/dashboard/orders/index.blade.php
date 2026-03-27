{{-- resources/views/dashboard/orders/index.blade.php --}}
@extends('layout.layout')

@php
    $title    = 'Orders';
    $subTitle = 'Orders';
    $script   = '<script>
                    let table = new DataTable("#ordersTable", {
                        responsive: true,
                        scrollX: false,
                        autoWidth: false,
                        paging: false,
                        info: false,
                        columnDefs: [{ orderable: false, targets: [0, 6] }]
                    });
                    document.getElementById("checkAll").addEventListener("change", function () {
                        document.querySelectorAll(".row-check").forEach(cb => cb.checked = this.checked);
                    });
                 </script>';
@endphp

@section('content')

{{-- ── Status Filter Tabs ── --}}
<div class="d-flex flex-wrap gap-2 mb-4">
    @php
        $statuses = [
            'all'        => ['label' => 'All',        'class' => 'btn-outline-secondary'],
            'pending'    => ['label' => 'Pending',    'class' => 'btn-outline-warning'],
            'confirmed'  => ['label' => 'Confirmed',  'class' => 'btn-outline-success'],
            'processing' => ['label' => 'Processing', 'class' => 'btn-outline-info'],
            'shipped'    => ['label' => 'Shipped',    'class' => 'btn-outline-primary'],
            'delivered'  => ['label' => 'Delivered',  'class' => 'btn-outline-success'],
            'cancelled'  => ['label' => 'Cancelled',  'class' => 'btn-outline-danger'],
        ];
        $currentStatus = request('status', 'all');
    @endphp
    @foreach($statuses as $key => $s)
        <a href="{{ route('dashboard.orders.index', array_merge(request()->query(), ['status' => $key === 'all' ? null : $key])) }}"
           class="btn btn-sm {{ $currentStatus === $key || ($key === 'all' && !request('status')) ? str_replace('outline-', '', $s['class']) : $s['class'] }}">
            {{ $s['label'] }}
            <span class="badge bg-white text-dark ms-1">{{ $statusCounts[$key] }}</span>
        </a>
    @endforeach
</div>

<div class="card basic-data-table">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">
            {{ request('status') ? ucfirst(request('status')) . ' Orders' : 'All Orders' }}
        </h5>
        {{-- Search --}}
        <form method="GET" action="{{ route('dashboard.orders.index') }}" class="d-flex gap-2">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control form-control-sm" placeholder="Search order # or name..."
                   style="width:220px;">
            <button type="submit" class="btn btn-primary btn-sm">Search</button>
            @if(request('search'))
                <a href="{{ route('dashboard.orders.index', request('status') ? ['status' => request('status')] : []) }}"
                   class="btn btn-outline-secondary btn-sm">Clear</a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0" id="ordersTable" style="width:100%">
                <thead>
                    <tr>
                        <th>
                            <div class="form-check style-check d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                                <label class="form-check-label">S.L</label>
                            </div>
                        </th>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $i => $order)
                    <tr>
                        <td>
                            <div class="form-check style-check d-flex align-items-center">
                                <input class="form-check-input row-check" type="checkbox">
                                <label class="form-check-label">
                                    {{ str_pad($orders->firstItem() + $i, 2, '0', STR_PAD_LEFT) }}
                                </label>
                            </div>
                        </td>

                        <td>
                            <span class="fw-semibold text-primary-600">{{ $order->order_number }}</span>
                        </td>

                        <td>
                            <div>
                                <h6 class="text-md mb-0 fw-medium">{{ $order->name }}</h6>
                                <span class="text-sm text-secondary-light">{{ $order->phone }}</span>
                            </div>
                        </td>

                        <td>
                            <span class="bg-neutral-200 text-neutral-600 px-12 py-4 rounded-pill fw-medium text-sm">
                                {{ $order->items_count }} Items
                            </span>
                        </td>

                        <td>
                            <span class="fw-semibold text-success-main">
                                ₹{{ number_format($order->total_amount, 2) }}
                            </span>
                        </td>

                        <td>
                            @php
                                $badges = [
                                    'pending'    => 'bg-warning-focus text-warning-main',
                                    'confirmed'  => 'bg-success-focus text-success-main',
                                    'processing' => 'bg-info-focus text-info-main',
                                    'shipped'    => 'bg-primary-focus text-primary-600',
                                    'delivered'  => 'bg-success-focus text-success-main',
                                    'cancelled'  => 'bg-danger-focus text-danger-main',
                                ];
                                $cls = $badges[$order->status] ?? 'bg-neutral-200 text-neutral-600';
                            @endphp
                            <span class="{{ $cls }} px-12 py-4 rounded-pill fw-medium text-sm">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>

                        <td>{{ $order->created_at->format('d M Y') }}</td>

                        <td>
                            <a href="{{ route('dashboard.orders.show', $order->order_number) }}"
                               class="w-32-px h-32-px bg-info-focus text-info-main rounded-circle d-inline-flex align-items-center justify-content-center"
                               title="View Order">
                                <iconify-icon icon="lucide:eye"></iconify-icon>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">No orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
        <div class="p-3 d-flex justify-content-end">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

@endsection