@extends('layout.layout')

@php
    $title = isset($product) ? 'Edit Product' : 'Product Add';
    $subTitle = 'Products';
@endphp

@push('styles')
    <style>
        :root {
            --green: #2d7a45;
            --green-light: #e8f5ed;
            --border: #dee2e6;
            --radius: 10px;
        }

        .page-header {
            background: linear-gradient(135deg, #2d7a45 0%, #1a5c30 100%);
            color: #fff;
            padding: 1.5rem 2rem;
            border-radius: var(--radius);
            margin-bottom: 1.8rem;
        }

        .page-header h1 {
            font-size: 1.5rem;
            margin: 0;
        }

        .page-header p {
            margin: .25rem 0 0;
            opacity: .8;
            font-size: .9rem;
        }

        .card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            margin-bottom: 1.6rem;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
        }

        .card-header {
            padding: .9rem 1.4rem;
            border-bottom: 1px solid var(--border);
            background: var(--green-light);
            border-radius: var(--radius) var(--radius) 0 0;
            font-weight: 600;
            color: var(--green);
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .card-body {
            padding: 1.4rem;
        }

        .form-label {
            font-weight: 500;
            font-size: .875rem;
            color: #495057;
            margin-bottom: .35rem;
        }

        .form-control,
        .form-select {
            border: 1px solid #ced4da;
            border-radius: 7px;
            font-size: .9rem;
            padding: .5rem .75rem;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(45, 122, 69, .15);
        }

        textarea.form-control {
            min-height: 130px;
            resize: vertical;
        }

        .variation-row {
            background: #fafafa;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            position: relative;
        }

        .variation-row .remove-variation {
            position: absolute;
            top: .6rem;
            right: .6rem;
            background: #ff4d4f;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 26px;
            height: 26px;
            font-size: .85rem;
            cursor: pointer;
            line-height: 26px;
            text-align: center;
            padding: 0;
        }

        .img-preview-grid {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            margin-top: .75rem;
        }

        .img-preview-grid .preview-thumb {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 6px;
            border: 2px solid var(--border);
        }

        .tag-pills {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
            margin-top: .5rem;
        }

        .tag-pill {
            background: var(--green-light);
            color: var(--green);
            border: 1px solid #a8d5b5;
            border-radius: 20px;
            padding: .2rem .7rem;
            font-size: .8rem;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        .tag-pill button {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            font-size: .9rem;
            padding: 0;
            line-height: 1;
        }

        .btn-primary {
            background: var(--green);
            border-color: var(--green);
            padding: .55rem 1.6rem;
            border-radius: 7px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #1a5c30;
            border-color: #1a5c30;
        }

        .btn-outline-secondary {
            border-radius: 7px;
        }

        .btn-add-variation {
            background: var(--green-light);
            color: var(--green);
            border: 2px dashed #a8d5b5;
            border-radius: 8px;
            padding: .5rem 1.2rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background .2s;
        }

        .btn-add-variation:hover {
            background: #d4edda;
        }

        .existing-img {
            position: relative;
            display: inline-block;
        }

        .existing-img img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 6px;
            border: 2px solid var(--border);
        }

        .existing-img .del-img {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #ff4d4f;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: .7rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .invalid-feedback {
            display: block;
        }

        .var-badge {
            display: inline-block;
            background: var(--green);
            color: #fff;
            border-radius: 6px;
            padding: .15rem .6rem;
            font-size: .75rem;
            font-weight: 600;
            margin-bottom: .5rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">

        <div class="page-header mb-4">
            <h1>{{ isset($product) ? 'Edit: ' . $product->name : 'Add New Product' }}</h1>
            <p>Fill in all the details below and click Save.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form
            action="{{ isset($product) ? route('dashboard.products.update', $product) : route('dashboard.products.store') }}"
            method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            @if (isset($product))
                @method('PUT')
            @endif

            <div class="row">

                {{-- LEFT COLUMN --}}
                <div class="col-lg-8">

                    {{-- Basic Info --}}
                    <div class="card mb-4">
                        <div class="card-header">Basic Information</div>
                        <div class="card-body">
                            <div class="row g-3">

                                <div class="col-md-8">
                                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $product->name ?? '') }}"
                                        placeholder="e.g. Bhoomi Star" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">SKU <span class="text-danger">*</span></label>
                                    <input type="text" name="sku"
                                        class="form-control @error('sku') is-invalid @enderror"
                                        value="{{ old('sku', $product->sku ?? '') }}"
                                        placeholder="e.g. BhoomiStar10" required>
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-select">
                                        <option value="">— Select Category —</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Brand</label>
                                    <select name="brand_id" class="form-select">
                                        <option value="">— Select Brand —</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label">Technical Content</label>
                                    <input type="text" name="technical_content" class="form-control"
                                        value="{{ old('technical_content', $product->technical_content ?? '') }}"
                                        placeholder="e.g. BIO SEA WEED EXTRACT">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select" required>
                                        @foreach (['active' => 'Active', 'inactive' => 'Inactive', 'draft' => 'Draft'] as $val => $label)
                                            <option value="{{ $val }}"
                                                {{ old('status', $product->status ?? 'active') === $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Short Description</label>
                                    <textarea name="short_description" class="form-control" rows="2"
                                        placeholder="One-line summary shown in listings...">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Full Description</label>
                                    <textarea name="description" class="form-control" rows="6"
                                        placeholder="Detailed product description...">{{ old('description', $product->description ?? '') }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">YouTube / Video URL</label>
                                    <input type="url" name="video_url"
                                        class="form-control @error('video_url') is-invalid @enderror"
                                        value="{{ old('video_url', $product->video_url ?? '') }}"
                                        placeholder="https://youtube.com/shorts/...">
                                    @error('video_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- VARIATIONS --}}
                    <div class="card mb-4">
                        <div class="card-header">Pack / Variations
                            <small class="ms-2 fw-normal text-muted">(Add each pack size as a variation)</small>
                        </div>
                        <div class="card-body">

                            <div id="variationsContainer">
                                @if (isset($product) && $product->variations->isNotEmpty())
                                    @foreach ($product->variations as $i => $var)
                                        <div class="variation-row" id="var_row_{{ $var->id }}">
                                            <span class="var-badge">Variation #{{ $i + 1 }}</span>
                                            <input type="hidden" name="variations[{{ $i }}][id]"
                                                value="{{ $var->id }}">
                                            <button type="button" class="remove-variation"
                                                onclick="removeExistingVariation({{ $var->id }}, this)"
                                                title="Remove">✕</button>

                                            <div class="row g-2">
                                                <div class="col-6 col-md-3">
                                                    <label class="form-label">Attribute Name</label>
                                                    <input type="text"
                                                        name="variations[{{ $i }}][attribute_name]"
                                                        class="form-control" value="{{ $var->attribute_name }}"
                                                        placeholder="Pack" required>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <label class="form-label">Value</label>
                                                    <input type="text"
                                                        name="variations[{{ $i }}][attribute_value]"
                                                        class="form-control" value="{{ $var->attribute_value }}"
                                                        placeholder="10 KG" required>
                                                </div>
                                                <div class="col-6 col-md-2">
                                                    <label class="form-label">SKU</label>
                                                    <input type="text" name="variations[{{ $i }}][sku]"
                                                        class="form-control" value="{{ $var->sku }}" required>
                                                </div>
                                                <div class="col-6 col-md-2">
                                                    <label class="form-label">Price (₹)</label>
                                                    <input type="number" name="variations[{{ $i }}][price]"
                                                        class="form-control" value="{{ $var->price }}"
                                                        min="0" step="0.01" required>
                                                </div>
                                                <div class="col-6 col-md-2">
                                                    <label class="form-label">Weight (kg)</label>
                                                    <input type="number" name="variations[{{ $i }}][weight]"
                                                        class="form-control" value="{{ $var->weight }}"
                                                        min="0" step="0.01">
                                                </div>
                                                <div class="col-6 col-md-2">
                                                    <label class="form-label">Stock</label>
                                                    <input type="number"
                                                        name="variations[{{ $i }}][stock_quantity]"
                                                        class="form-control" value="{{ $var->stock_quantity }}"
                                                        min="0" required>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label class="form-label">Variation Image</label>
                                                    <input type="file" name="variations[{{ $i }}][image]"
                                                        class="form-control" accept="image/*">
                                                    @if ($var->image_path)
                                                        <img src="{{ Storage::url($var->image_path) }}"
                                                            style="width:60px;height:60px;object-fit:cover;border-radius:6px;margin-top:5px;border:1px solid #dee2e6;">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <button type="button" class="btn-add-variation mt-2" onclick="addVariation()">
                                + Add Variation / Pack Size
                            </button>

                            <div class="mt-2 text-muted" style="font-size:.82rem;">
                                 Example: Add separate rows for <strong>5 KG</strong>, <strong>10 KG</strong>,
                                <strong>20 KG</strong> packs — each with its own SKU, price, and stock.
                            </div>

                        </div>
                    </div>

                    {{-- SEO --}}
                    <div class="card mb-4">
                        <div class="card-header"> SEO</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" name="meta_title" class="form-control"
                                        value="{{ old('meta_title', $product->meta_title ?? '') }}"
                                        placeholder="SEO page title">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Meta Description</label>
                                    <textarea name="meta_description" class="form-control" rows="3"
                                        placeholder="SEO description for search engines...">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>{{-- /col-lg-8 --}}


                {{-- RIGHT COLUMN --}}
                <div class="col-lg-4">

                    {{-- Pricing --}}
                    <div class="card mb-4">
                        <div class="card-header">Pricing</div>
                        <div class="card-body">
                            <label class="form-label">Base / Starting Price (₹) <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="base_price"
                                class="form-control @error('base_price') is-invalid @enderror"
                                value="{{ old('base_price', $product->base_price ?? '') }}"
                                min="0" step="0.01" placeholder="500.00" required>
                            @error('base_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Each variation has its own price set above.</small>

                            <label class="form-label mt-3">Shipping Charge (₹)</label>
                            <input type="number" name="shipping_charge"
                                class="form-control @error('shipping_charge') is-invalid @enderror"
                                value="{{ old('shipping_charge', $product->shipping_charge ?? 0) }}"
                                min="0" step="0.01" placeholder="0.00">
                            @error('shipping_charge')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">Leave as 0 for free shipping, or enter the shipping charge amount.</small>

                            <label class="form-label mt-3">Tax Rate (%) (GST/VAT)</label>
                            <input type="number" name="tax_rate"
                                class="form-control @error('tax_rate') is-invalid @enderror"
                                value="{{ old('tax_rate', $product->tax_rate ?? 0) }}"
                                min="0" max="100" step="0.01" placeholder="0.00">
                            @error('tax_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">e.g., 5% GST, 18% VAT. Leave as 0 for no tax.</small>
                        </div>
                    </div>

                    {{-- Featured Image --}}
                    <div class="card mb-4">
                        <div class="card-header">Featured Image</div>
                        <div class="card-body">
                            @if (isset($product) && $product->featured_image)
                                <img src="{{ Storage::url($product->featured_image) }}"
                                    class="img-fluid rounded mb-2"
                                    style="max-height:180px;object-fit:cover;width:100%;">
                            @endif
                            <input type="file" name="featured_image" class="form-control" accept="image/*"
                                onchange="previewImage(this, 'featuredPreview')">
                            <img id="featuredPreview" class="img-fluid rounded mt-2"
                                style="display:none;max-height:180px;object-fit:cover;width:100%;">
                        </div>
                    </div>

                    {{-- Gallery --}}
                    <div class="card mb-4">
                        <div class="card-header">Gallery Images</div>
                        <div class="card-body">
                            @if (isset($product) && $product->images->isNotEmpty())
                                <div class="img-preview-grid mb-2" id="existingGallery">
                                    @foreach ($product->images as $img)
                                        <div class="existing-img" id="existingImg_{{ $img->id }}">
                                            <img src="{{ Storage::url($img->image_path) }}">
                                            <button type="button" class="del-img"
                                                onclick="deleteImage({{ $img->id }}, this)"
                                                title="Remove">✕</button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple
                                onchange="previewGallery(this)">
                            <div id="galleryPreviews" class="img-preview-grid"></div>
                            <small class="text-muted">Select multiple images (Ctrl+click).</small>
                        </div>
                    </div>

                    {{-- Tags --}}
                    <div class="card mb-4">
                        <div class="card-header">Tags</div>
                        <div class="card-body">
                            <div class="input-group">
                                <input type="text" id="tagInput" class="form-control"
                                    placeholder="Type a tag and press Enter">
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="addTag()">Add</button>
                            </div>
                            <div class="tag-pills mt-2" id="tagPills"></div>
                            <div id="tagInputsContainer"></div>

                            @if (isset($product))
                                <script>
                                    window._existingTags = @json($product->tags->pluck('name'));
                                </script>
                            @endif

                            <div class="mt-3">
                                <small class="text-muted d-block mb-1">Suggestions:</small>
                                <div style="display:flex;flex-wrap:wrap;gap:.3rem;">
                                    @foreach ($tags->take(20) as $t)
                                        <span class="badge bg-light text-dark border"
                                            style="cursor:pointer;font-size:.75rem;"
                                            onclick="addTagByName('{{ addslashes($t->name) }}')">{{ $t->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="card mb-4">
                        <div class="card-body d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                 {{ isset($product) ? 'Update Product' : 'Save Product' }}
                            </button>
                            <a href="{{ route('dashboard.products.index') }}"
                                class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </div>

                </div>{{-- /col-lg-4 --}}
            </div>{{-- /row --}}
        </form>
    </div>
@endsection


@push('scripts')
<script>
    // varIndex starts from existing variation count
    var varIndex = {{ isset($product) ? $product->variations->count() : 0 }};

    function addVariation() {
        var i = varIndex++;

        var row = document.createElement('div');
        row.className = 'variation-row';
        row.id = 'newVar_' + i;

        // Build fields one by one using createElement — zero Blade conflict
        row.innerHTML =
            '<span class="var-badge">New Variation</span>' +
            '<input type="hidden" name="variations[' + i + '][id]" value="">' +
            '<button type="button" class="remove-variation" onclick="this.closest(\'.variation-row\').remove()" title="Remove">&#x2715;</button>' +
            '<div class="row g-2 mt-1">' +

                '<div class="col-6 col-md-3">' +
                    '<label class="form-label">Attribute Name</label>' +
                    '<input type="text" name="variations[' + i + '][attribute_name]" class="form-control" value="Pack" placeholder="Pack" required>' +
                '</div>' +

                '<div class="col-6 col-md-3">' +
                    '<label class="form-label">Value <span class="text-danger">*</span></label>' +
                    '<input type="text" name="variations[' + i + '][attribute_value]" class="form-control" placeholder="e.g. 5 KG" required>' +
                '</div>' +

                '<div class="col-6 col-md-2">' +
                    '<label class="form-label">SKU <span class="text-danger">*</span></label>' +
                    '<input type="text" name="variations[' + i + '][sku]" class="form-control" placeholder="e.g. PROD5KG" required>' +
                '</div>' +

                '<div class="col-6 col-md-2">' +
                    '<label class="form-label">Price (&#8377;) <span class="text-danger">*</span></label>' +
                    '<input type="number" name="variations[' + i + '][price]" class="form-control" min="0" step="0.01" placeholder="500" required>' +
                '</div>' +

                '<div class="col-6 col-md-2">' +
                    '<label class="form-label">Weight (kg)</label>' +
                    '<input type="number" name="variations[' + i + '][weight]" class="form-control" min="0" step="0.01" placeholder="5">' +
                '</div>' +

                '<div class="col-6 col-md-2">' +
                    '<label class="form-label">Stock <span class="text-danger">*</span></label>' +
                    '<input type="number" name="variations[' + i + '][stock_quantity]" class="form-control" min="0" placeholder="100" required>' +
                '</div>' +

                '<div class="col-12 col-md-4">' +
                    '<label class="form-label">Variation Image</label>' +
                    '<input type="file" name="variations[' + i + '][image]" class="form-control" accept="image/*">' +
                '</div>' +

            '</div>';

        document.getElementById('variationsContainer').appendChild(row);
    }

    // Remove saved variation via AJAX
    function removeExistingVariation(id, btn) {
        if (!confirm('Remove this variation?')) return;
        fetch('/dashboard/product-variations/' + id, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if (d.success) btn.closest('.variation-row').remove();
        })
        .catch(function() { alert('Error removing variation. Try again.'); });
    }

    // Featured image preview
    function previewImage(input, previewId) {
        var prev = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                prev.src = e.target.result;
                prev.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Gallery preview
    function previewGallery(input) {
        var container = document.getElementById('galleryPreviews');
        container.innerHTML = '';
        Array.from(input.files).forEach(function(file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview-thumb';
                container.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }

    // Delete existing gallery image via AJAX
    function deleteImage(id, btn) {
        if (!confirm('Remove this image?')) return;
        fetch('/dashboard/product-images/' + id, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if (d.success) document.getElementById('existingImg_' + id).remove();
        });
    }

    // ── TAG MANAGEMENT ───────────────────────────────────────────────
    var activeTags = new Set();

    function renderTags() {
        var pills  = document.getElementById('tagPills');
        var inputs = document.getElementById('tagInputsContainer');
        pills.innerHTML  = '';
        inputs.innerHTML = '';

        activeTags.forEach(function(tag) {
            var safeName = tag.replace(/\\/g, '\\\\').replace(/'/g, "\\'");

            var pill = document.createElement('span');
            pill.className = 'tag-pill';
            pill.innerHTML = tag + '<button type="button" onclick="removeTag(\'' + safeName + '\')">&#x2715;</button>';
            pills.appendChild(pill);

            var hidden = document.createElement('input');
            hidden.type  = 'hidden';
            hidden.name  = 'tags[]';
            hidden.value = tag;
            inputs.appendChild(hidden);
        });
    }

    function addTagByName(name) {
        if (name && !activeTags.has(name)) {
            activeTags.add(name);
            renderTags();
        }
    }

    function addTag() {
        var input = document.getElementById('tagInput');
        var val   = input.value.trim();
        if (val && !activeTags.has(val)) {
            activeTags.add(val);
            renderTags();
        }
        input.value = '';
    }

    function removeTag(name) {
        activeTags.delete(name);
        renderTags();
    }

    document.getElementById('tagInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addTag();
        }
    });

    // Pre-fill existing tags in edit mode
    (window._existingTags || []).forEach(function(t) { activeTags.add(t); });
    renderTags();
</script>
@endpush