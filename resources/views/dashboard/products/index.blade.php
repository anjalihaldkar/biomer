{{-- resources/views/dashboard/products/index.blade.php --}}
@extends('layout.layout')

@php
    $title    = 'Products';
    $subTitle = 'Products';
    $script   = '<script>
                    let table = new DataTable("#dataTable", {
                        responsive: true,
                        scrollX: false,
                        autoWidth: false,
                        columnDefs: [
                            { orderable: false, targets: [0, 1, 6, 9] }
                        ]
                    });

                    // Select All checkbox
                    document.getElementById("checkAll").addEventListener("change", function () {
                        document.querySelectorAll(".row-check").forEach(cb => cb.checked = this.checked);
                    });
                 </script>';
@endphp

@section('content')

<div class="card basic-data-table">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title mb-0">Products</h5>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('dashboard.tags.index') }}" class="btn btn-outline-secondary btn-sm">
                🏷️ Tags
            </a>
            <a href="{{ route('dashboard.products.create') }}" class="btn btn-primary btn-sm">
                + Add Product
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
                        <th scope="col">Product Name</th>
                        <th scope="col">Brand</th>
                        <th scope="col">Category</th>
                        <th scope="col">Price</th>
                        <th scope="col">Variations</th>
                        <th scope="col">Status</th>
                        <th scope="col">Created</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $i => $product)
                    <tr>
                        {{-- Checkbox + Serial --}}
                        <td>
                            <div class="form-check style-check d-flex align-items-center">
                                <input class="form-check-input row-check" type="checkbox">
                                <label class="form-check-label">
                                    {{ str_pad($products->firstItem() + $i, 2, '0', STR_PAD_LEFT) }}
                                </label>
                            </div>
                        </td>

                        {{-- Image --}}
                        <td>
                            @if($product->featured_image)
                                <img src="{{ Storage::url($product->featured_image) }}"
                                     alt="{{ $product->name }}"
                                     class="flex-shrink-0 radius-8"
                                     style="width:40px;height:40px;object-fit:cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center radius-8 bg-neutral-200"
                                     style="width:40px;height:40px;font-size:1.1rem;">📦</div>
                            @endif
                        </td>

                        {{-- Product Name + SKU --}}
                        <td>
                            <h6 class="text-md mb-0 fw-medium">{{ $product->name }}</h6>
                            @if($product->sku)
                                <span class="text-sm text-secondary-light"><code>{{ $product->sku }}</code></span>
                            @endif
                        </td>

                        {{-- Brand --}}
                        <td>{{ $product->brand->name ?? '—' }}</td>

                        {{-- Category --}}
                        <td>{{ $product->category->name ?? '—' }}</td>

                        {{-- Price --}}
                        <td>₹{{ number_format($product->base_price, 2) }}</td>

                        {{-- Variations --}}
                        <td>
                            <a href="{{ route('dashboard.products.variations.index', $product) }}"
                               class="bg-primary-light text-primary-600 px-12 py-4 rounded-pill fw-medium text-sm text-decoration-none">
                                {{ $product->variations->count() }} Variations
                            </a>
                        </td>

                        {{-- Status --}}
                        <td>
                            @if($product->status === 'active')
                                <span class="bg-success-focus text-success-main px-24 py-4 rounded-pill fw-medium text-sm">Active</span>
                            @elseif($product->status === 'inactive')
                                <span class="bg-danger-focus text-danger-main px-24 py-4 rounded-pill fw-medium text-sm">Inactive</span>
                            @else
                                <span class="bg-warning-focus text-warning-main px-24 py-4 rounded-pill fw-medium text-sm">Draft</span>
                            @endif
                        </td>

                        {{-- Created Date --}}
                        <td>{{ $product->created_at->format('d M Y') }}</td>

                        {{-- Actions --}}
                        <td>
                            {{-- Variations --}}
                            <a href="{{ route('dashboard.products.variations.index', $product) }}"
                               class="w-32-px h-32-px bg-info-focus text-info-main rounded-circle d-inline-flex align-items-center justify-content-center"
                               title="Manage Variations">
                                <iconify-icon icon="fluent:layer-diagonal-20-regular"></iconify-icon>
                            </a>

                            {{-- Edit --}}
                            <a href="{{ route('dashboard.products.edit', $product) }}"
                               class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                               title="Edit Product">
                                <iconify-icon icon="lucide:edit"></iconify-icon>
                            </a>

                            {{-- Delete --}}
                            <form action="{{ route('dashboard.products.destroy', $product) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete product \'{{ addslashes($product->name) }}\'?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center border-0"
                                        style="background:transparent"
                                        title="Delete Product">
                                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            No products found.
                            <a href="{{ route('dashboard.products.create') }}">Add your first product!</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>{{-- end table-responsive --}}
    </div>
</div>

@endsection