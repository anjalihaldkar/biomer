{{-- resources/views/dashboard/stock/edit.blade.php --}}
@extends('layout.layout')

@php
    $title    = 'Update Stock';
    $subTitle = 'Update Stock';
@endphp

@section('content')

<div class="card" style="max-width:500px; margin:auto;">
    <div class="card-header">
        <h5 class="card-title mb-0">Update Stock Quantity</h5>
    </div>
    <div class="card-body">

        <div class="mb-3">
            <p class="text-muted mb-1">Product</p>
            <h6 class="fw-bold">
                @if($type === 'variation')
                    {{ $item->product->name }} — {{ $item->attribute_value }}
                @else
                    {{ $item->name }}
                @endif
            </h6>
        </div>

        <div class="mb-3">
            <p class="text-muted mb-1">Current Stock</p>
            <h4 class="fw-bold {{ $item->stock_quantity === 0 ? 'text-danger-main' : ($item->stock_quantity <= 5 ? 'text-warning-main' : 'text-success-main') }}">
                {{ $item->stock_quantity }} units
            </h4>
        </div>

        <form action="{{ route('dashboard.stock.update') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="hidden" name="id"   value="{{ $item->id }}">

            <div class="mb-3">
                <label class="form-label fw-semibold">New Stock Quantity</label>
                <input type="number" name="stock_quantity" min="0"
                       value="{{ $item->stock_quantity }}"
                       class="form-control @error('stock_quantity') is-invalid @enderror">
                @error('stock_quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-20">Update Stock</button>
                <a href="{{ route('dashboard.stock.index') }}" class="btn btn-outline-secondary px-20">Cancel</a>
            </div>
        </form>

    </div>
</div>

@endsection