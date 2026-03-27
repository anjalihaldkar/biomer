{{-- resources/views/dashboard/stock/index.blade.php --}}
@extends('layout.layout')

@php
    $title    = 'Stock Management';
    $subTitle = 'Stock Management';
    $script   = '<script>
                    let table = new DataTable("#stockTable", {
                        responsive: true,
                        scrollX: false,
                        autoWidth: false,
                        columnDefs: [{ orderable: false, targets: [0, 5] }]
                    });
                 </script>';
@endphp

@section('content')

{{-- ── Stats Cards ── --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center py-3">
            <h6 class="text-muted mb-1">Total Products</h6>
            <h3 class="fw-bold text-primary-600">{{ $totalProducts }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center py-3">
            <h6 class="text-muted mb-1">In Stock</h6>
            <h3 class="fw-bold text-success-main">{{ $inStock }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center py-3">
            <h6 class="text-muted mb-1">Low Stock</h6>
            <h3 class="fw-bold text-warning-main">{{ $lowStock }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center py-3">
            <h6 class="text-muted mb-1">Out of Stock</h6>
            <h3 class="fw-bold text-danger-main">{{ $outOfStock }}</h3>
        </div>
    </div>
</div>

{{-- ── Stock Table ── --}}
<div class="card basic-data-table">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Stock Overview</h5>
        {{-- Filter Buttons --}}
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard.stock.index') }}"
               class="btn btn-sm {{ !request('filter') ? 'btn-primary' : 'btn-outline-secondary' }}">
                All
            </a>
            <a href="{{ route('dashboard.stock.index', ['filter' => 'low']) }}"
               class="btn btn-sm {{ request('filter') === 'low' ? 'btn-warning' : 'btn-outline-warning' }}">
                ⚠ Low Stock
            </a>
            <a href="{{ route('dashboard.stock.index', ['filter' => 'out']) }}"
               class="btn btn-sm {{ request('filter') === 'out' ? 'btn-danger' : 'btn-outline-danger' }}">
                ✕ Out of Stock
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3 mb-0">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0" id="stockTable" data-page-length='10' style="width:100%">
                <thead>
                    <tr>
                        <th>
                            <div class="form-check style-check d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                                <label class="form-check-label">S.L</label>
                            </div>
                        </th>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Variation</th>
                        <th>Stock Qty</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $i => $row)
                    <tr>
                        {{-- S.L --}}
                        <td>
                            <div class="form-check style-check d-flex align-items-center">
                                <input class="form-check-input row-check" type="checkbox">
                                <label class="form-check-label">
                                    {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                                </label>
                            </div>
                        </td>

                        {{-- Product --}}
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="w-36-px h-36-px rounded-circle bg-primary-100 d-flex align-items-center justify-content-center fw-bold text-primary-600"
                                     style="font-size:0.8rem; min-width:36px;">
                                    {{ strtoupper(substr($row['product_name'], 0, 1)) }}
                                </div>
                                <div class="fw-medium">{{ $row['product_name'] }}</div>
                            </div>
                        </td>

                        {{-- SKU --}}
                        <td><code class="text-sm">{{ $row['sku'] ?? '—' }}</code></td>

                        {{-- Variation --}}
                        <td>
                            @if($row['variation_name'])
                                <span class="bg-primary-focus text-primary-600 px-12 py-4 rounded-pill fw-medium text-sm">
                                    {{ $row['variation_name'] }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- Stock Qty --}}
                        <td>
                            <span class="fw-bold fs-6 {{ $row['stock'] === 0 ? 'text-danger-main' : ($row['stock'] <= 5 ? 'text-warning-main' : 'text-success-main') }}">
                                {{ $row['stock'] }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td>
                            @if($row['stock'] === 0)
                                <span class="bg-danger-focus text-danger-main px-12 py-4 rounded-pill fw-medium text-sm">
                                    Out of Stock
                                </span>
                            @elseif($row['stock'] <= 5)
                                <span class="bg-warning-focus text-warning-main px-12 py-4 rounded-pill fw-medium text-sm">
                                    Low Stock
                                </span>
                            @else
                                <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-medium text-sm">
                                    In Stock
                                </span>
                            @endif
                        </td>

                        {{-- Action --}}
                        <td>
                            <a href="{{ route('dashboard.stock.edit', ['type' => $row['type'], 'id' => $row['id']]) }}"
                               class="w-32-px h-32-px bg-info-focus text-info-main rounded-circle d-inline-flex align-items-center justify-content-center"
                               title="Update Stock">
                                <iconify-icon icon="lucide:edit"></iconify-icon>
                            </a>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">No stock data found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection