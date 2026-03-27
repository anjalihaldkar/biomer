{{-- resources/views/dashboard/tags/edit.blade.php --}}
@extends('layout.layout')

@php
    $title    = 'Edit Tag';
    $subTitle = 'Edit Tag';
@endphp

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-5 col-md-7 col-12">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold mb-0">Edit Tag</h6>
            <a href="{{ route('dashboard.tags.index') }}"
               class="btn btn-outline-secondary btn-sm">
                ← Back
            </a>
        </div>

        {{-- Error Alert --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Card --}}
        <div class="card basic-data-table">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    Tag Details
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.tags.update', $tag) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Tag Name --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Tag Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $tag->name) }}"
                               placeholder="e.g. Cotton, Summer, Sale"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Slug</label>
                        <input type="text"
                               name="slug"
                               id="slugInput"
                               class="form-control @error('slug') is-invalid @enderror"
                               value="{{ old('slug', $tag->slug) }}"
                               placeholder="e.g. cotton-summer-sale">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Auto-generated from name. Lowercase letters, numbers & hyphens only.</small>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                              <iconify-icon icon="lucide:save"></iconify-icon>
                              Update Tag
                        </button>
                        <a href="{{ route('dashboard.tags.index') }}"
                           class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>

            {{-- Footer Info --}}
           

        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    const nameInput = document.querySelector('input[name="name"]');
    const slugInput = document.getElementById('slugInput');

    nameInput.addEventListener('input', function () {
        if (!slugInput.dataset.manual) {
            slugInput.value = this.value.toLowerCase().trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-');
        }
    });

    slugInput.addEventListener('input', function () {
        this.dataset.manual = 'true';
    });
</script>
@endpush