{{-- resources/views/dashboard/coupons/edit.blade.php --}}
@extends('layout.layout')

@section('title', 'Edit Coupon')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 fw-bold">Edit Coupon</h2>
            <small class="text-muted">Update promotional code: <strong>{{ $coupon->code }}</strong></small>
        </div>
        <a href="{{ route('dashboard.coupons.index') }}" class="btn btn-outline-secondary">← Back to List</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('dashboard.coupons.update', $coupon) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount (₹)</option>
                            <option value="percent" {{ old('type', $coupon->type) == 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                        <input type="number" name="value" class="form-control" value="{{ old('value', (float)$coupon->value) }}" step="0.01" min="0" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Minimum Order Amount <span class="text-danger">*</span></label>
                        <input type="number" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', (float)$coupon->min_order_amount) }}" step="0.01" min="0" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Usage Limit (Total uses)</label>
                        <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at', $coupon->expires_at ? \Carbon\Carbon::parse($coupon->expires_at)->format('Y-m-d') : '') }}">
                    </div>

                    <div class="col-md-12 mt-3">
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActiveSwitch" {{ (old('is_active', $coupon->is_active)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActiveSwitch">Make Active</label>
                        </div>
                    </div>

                    <div class="col-md-12 text-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">Update Coupon</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
