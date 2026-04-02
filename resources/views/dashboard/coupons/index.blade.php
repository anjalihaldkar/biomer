{{-- resources/views/dashboard/coupons/index.blade.php --}}
@extends('layout.layout')

@section('title', 'Coupons')

@section('content')
<div class="container-fluid py-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="mb-0 fw-bold">🏷️ Coupons</h2>
      <small class="text-muted">Manage promotional codes</small>
    </div>
    <a href="{{ route('dashboard.coupons.create') }}" class="btn btn-success px-4">+ Add Coupon</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">✅ {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:60px">#</th>
            <th>Code</th>
            <th>Type</th>
            <th>Value</th>
            <th>Min Order Amount</th>
            <th>Stats</th>
            <th>Status</th>
            <th>Expires</th>
            <th style="width:150px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($coupons as $coupon)
          <tr>
            <td class="text-muted small">{{ $coupon->id }}</td>
            <td><strong>{{ $coupon->code }}</strong></td>
            <td><span class="badge bg-secondary text-capitalize">{{ $coupon->type }}</span></td>
            <td>
              @if($coupon->type == 'percent')
                {{ $coupon->value }}%
              @else
                ₹{{ number_format($coupon->value, 2) }}
              @endif
            </td>
            <td>₹{{ number_format($coupon->min_order_amount, 2) }}</td>
            <td class="text-muted small">Used: {{ $coupon->used_count }}{{ $coupon->usage_limit ? '/'.$coupon->usage_limit : '' }}</td>
            <td>
              @if($coupon->is_active)
                  <span class="badge bg-success">Active</span>
              @else
                  <span class="badge bg-danger">Inactive</span>
              @endif
            </td>
            <td class="text-muted small">
                {{ $coupon->expires_at ? \Carbon\Carbon::parse($coupon->expires_at)->format('d M Y') : 'Never' }}
            </td>
            <td>
              <a href="{{ route('dashboard.coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
              <form action="{{ route('dashboard.coupons.destroy', $coupon) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Delete coupon \'{{ addslashes($coupon->code) }}\'?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Delete</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" class="text-center py-5 text-muted">No coupons yet. <a href="{{ route('dashboard.coupons.create') }}">Add one!</a></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="mt-3">{{ $coupons->links() }}</div>
</div>
@endsection
