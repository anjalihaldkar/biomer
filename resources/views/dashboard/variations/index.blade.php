{{-- resources/views/dashboard/variations/index.blade.php --}}
@extends('layout.layout')

@php
    $title    = 'Variations';
    $subTitle = $product->name;
    $script   = '<script>
                    let table = new DataTable("#dataTable", {
                        responsive: true,
                        scrollX: false,
                        autoWidth: false,
                        columnDefs: [
                            { orderable: false, targets: [0, 1, 7, 8] }
                        ]
                    });

                    document.getElementById("checkAll").addEventListener("change", function () {
                        document.querySelectorAll(".row-check").forEach(cb => cb.checked = this.checked);
                    });

                    // Toggle active status
                    document.querySelectorAll(".toggle-status").forEach(toggle => {
                        toggle.addEventListener("change", function () {
                            fetch(`/dashboard/product-variations/${this.dataset.id}/toggle`, {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content,
                                    "Content-Type": "application/json"
                                }
                            })
                            .then(r => r.json())
                            .then(d => { this.checked = d.is_active; });
                        });
                    });
                 </script>';
@endphp

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <a href="{{ route('dashboard.products.index') }}"
           class="text-secondary-light text-decoration-none text-sm">
            ← All Products
        </a>
        <h5 class="fw-bold mb-0 mt-1">
            📦 Variations:
            <span class="text-success-main">{{ $product->name }}</span>
        </h5>
        <small class="text-secondary-light">
            SKU: <code>{{ $product->sku }}</code> &nbsp;|&nbsp; Base Price: ₹{{ number_format($product->base_price, 2) }}
        </small>
    </div>
    <a href="{{ route('dashboard.products.variations.create', $product) }}" class="btn btn-primary btn-sm">
        + Add Variation
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        ✅ {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card basic-data-table">
    <div class="card-header">
        <h5 class="card-title mb-0">Variations List</h5>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table bordered-table mb-0" id="dataTable" data-page-length='10' style="width:100%">
                <thead>
                    <tr>
                        <th scope="col">
                            <div class="form-check style-check d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                                <label class="form-check-label">S.L</label>
                            </div>
                        </th>
                        <th scope="col">Image</th>
                        <th scope="col">SKU</th>
                        <th scope="col">Attribute</th>
                        <th scope="col">Value</th>
                        <th scope="col">Price (₹)</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Active</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($variations as $i => $var)
                    <tr>
                        {{-- Checkbox + Serial --}}
                        <td>
                            <div class="form-check style-check d-flex align-items-center">
                                <input class="form-check-input row-check" type="checkbox">
                                <label class="form-check-label">
                                    {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                                </label>
                            </div>
                        </td>

                        {{-- Image --}}
                        <td>
                            @if($var->image_path)
                                <img src="{{ Storage::url($var->image_path) }}"
                                     class="radius-8"
                                     style="width:44px;height:44px;object-fit:cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center radius-8 bg-neutral-200"
                                     style="width:44px;height:44px;font-size:1.1rem;">📦</div>
                            @endif
                        </td>

                        {{-- SKU --}}
                        <td><code class="text-secondary-light text-sm">{{ $var->sku }}</code></td>

                        {{-- Attribute --}}
                        <td>
                            <h6 class="text-md mb-0 fw-medium">{{ $var->attribute_name }}</h6>
                        </td>

                        {{-- Value --}}
                        <td>
                            <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-medium text-sm">
                                {{ $var->attribute_value }}
                            </span>
                        </td>

                        {{-- Price --}}
                        <td>
                            <span class="fw-semibold">₹{{ number_format($var->price, 2) }}</span>
                            @if($var->weight)
                                <br><small class="text-secondary-light">{{ $var->weight }} kg</small>
                            @endif
                        </td>

                        {{-- Stock --}}
                        <td>
                            @if($var->stock_quantity > 10)
                                <span class="bg-success-focus text-success-main px-12 py-4 rounded-pill fw-medium text-sm">
                                    {{ $var->stock_quantity }}
                                </span>
                            @elseif($var->stock_quantity > 0)
                                <span class="bg-warning-focus text-warning-main px-12 py-4 rounded-pill fw-medium text-sm">
                                    {{ $var->stock_quantity }}
                                </span>
                            @else
                                <span class="bg-danger-focus text-danger-main px-12 py-4 rounded-pill fw-medium text-sm">
                                    Out
                                </span>
                            @endif
                        </td>

                        {{-- Active Toggle --}}
                        <td>
                            <div class="form-check form-switch d-flex align-items-center gap-2">
                                <input class="form-check-input toggle-status" type="checkbox" role="switch"
                                       data-id="{{ $var->id }}"
                                       {{ $var->is_active ? 'checked' : '' }}
                                       style="cursor:pointer;width:2.5em;height:1.3em;">
                                <label class="text-sm {{ $var->is_active ? 'text-success-main' : 'text-secondary-light' }}">
                                    {{ $var->is_active ? 'Active' : 'Inactive' }}
                                </label>
                            </div>
                        </td>

                        {{-- Actions --}}
                        <td>
                            {{-- Edit --}}
                            <a href="{{ route('dashboard.products.variations.edit', [$product, $var]) }}"
                               class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                               title="Edit Variation">
                                <iconify-icon icon="lucide:edit"></iconify-icon>
                            </a>

                            {{-- Delete --}}
                            <form action="{{ route('dashboard.products.variations.destroy', [$product, $var]) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this variation?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center border-0"
                                        style="background:transparent"
                                        title="Delete Variation">
                                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            No variations yet.
                            <a href="{{ route('dashboard.products.variations.create', $product) }}">Add one!</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>{{-- end table-responsive --}}
    </div>
</div>

@endsection