{{-- resources/views/dashboard/pages/edit.blade.php --}}
@extends('layout.layout')

@php
    $title    = 'Edit Page';
    $subTitle = 'Edit Page';
@endphp

@section('content')

  {{-- Breadcrumb --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h6 class="mb-0"># {{ $page->title }}</h6>
      <small class="text-muted">{{ $page->slug }}</small>
    </div>
    <a href="{{ route('dashboard.pages.index') }}" class="btn btn-outline-secondary btn-sm">← Back to Pages</a>
  </div>

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <form action="{{ route('dashboard.pages.update', $page) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-4">

      {{-- ── LEFT COLUMN ─────────────────────────────────────────────── --}}
      <div class="col-lg-8">

        {{-- Page Information --}}
        <div class="card basic-data-table mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">Page Information</h5>
          </div>
          <div class="card-body">
            <div class="row g-3">

              <div class="col-12">
                <label class="form-label fw-semibold">Page Title <span class="text-danger">*</span></label>
                <input type="text" name="title"
                       class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title', $page->title) }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">URL Slug</label>
                <input type="text" disabled
                       class="form-control"
                       value="{{ $page->slug }}"
                       placeholder="Auto-generated from title">
                <small class="text-muted d-block mt-1">📍 Page URL: <code>{{ config('app.url') }}/{{ $page->slug }}</code></small>
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Page Content <span class="text-danger">*</span></label>
                <textarea name="content" rows="8"
                          class="form-control @error('content') is-invalid @enderror"
                          placeholder="Write your page content here (HTML supported)..." required>{{ old('content', $page->content) }}</textarea>
                @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted d-block mt-1">Main content of the page. You can use HTML tags.</small>
              </div>

            </div>
          </div>
        </div>

        {{-- SEO Settings --}}
        <div class="card basic-data-table mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">🔍 SEO Settings</h5>
          </div>
          <div class="card-body">
            <div class="row g-3">

              <div class="col-12">
                <label class="form-label fw-semibold">Meta Title <span class="text-muted">(50-60 chars)</span></label>
                <input type="text" name="meta_title" maxlength="255"
                       class="form-control @error('meta_title') is-invalid @enderror"
                       value="{{ old('meta_title', $page->meta_title) }}"
                       placeholder="E.g., About Our Solutions | Bharat Biomer">
                <small class="text-muted d-block mt-1">📊 <span id="meta_title_count">0</span>/255 chars</small>
                @error('meta_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Meta Description <span class="text-muted">(150-160 chars)</span></label>
                <textarea name="meta_description" rows="3" maxlength="500"
                          class="form-control @error('meta_description') is-invalid @enderror"
                          placeholder="Summary for search results...">{{ old('meta_description', $page->meta_description) }}</textarea>
                <small class="text-muted d-block mt-1">📊 <span id="meta_description_count">0</span>/500 chars</small>
                @error('meta_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Meta Keywords</label>
                <textarea name="meta_keyword" rows="3" maxlength="500"
                          class="form-control @error('meta_keyword') is-invalid @enderror"
                          placeholder="comma-separated keywords...">{{ old('meta_keyword', $page->meta_keyword) }}</textarea>
                <small class="text-muted d-block mt-1">📊 <span id="meta_keyword_count">0</span>/500 chars</small>
                @error('meta_keyword')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>

            </div>
          </div>
        </div>

      </div>

      {{-- ── RIGHT COLUMN ────────────────────────────────────────────── --}}
      <div class="col-lg-4">

        {{-- Page Settings --}}
        <div class="card basic-data-table mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">⚙️ Settings</h5>
          </div>
          <div class="card-body">
            <div class="row g-3">

              <div class="col-12">
                <label class="form-label fw-semibold">Status</label>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                    {{ old('status', $page->status) ? 'checked' : '' }}>
                  <label class="form-check-label" for="status">
                    Publish this page
                  </label>
                </div>
                <small class="text-muted d-block mt-2">
                  {{ old('status', $page->status) ? '✓ Published' : '○ Draft (not visible)' }}
                </small>
              </div>

            </div>
          </div>
        </div>

        {{-- Danger Zone --}}
        <div class="card basic-data-table border-danger mb-4">
          <div class="card-header bg-danger-focus">
            <h5 class="card-title mb-0 text-danger">🗑️ Danger Zone</h5>
          </div>
          <div class="card-body">
            <p class="text-muted text-sm mb-3">Permanently delete this page. This action cannot be undone.</p>
            <form action="{{ route('dashboard.pages.destroy', $page) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this page? This action cannot be undone.');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger w-100">
                <iconify-icon icon="lucide:trash-2"></iconify-icon> Delete Page
              </button>
            </form>
          </div>
        </div>

      </div>

    </div>

    {{-- Submit Actions --}}
    <div class="d-flex gap-2 justify-content-between mt-4">
      <a href="{{ route('dashboard.pages.index') }}" class="btn btn-outline-secondary">← Back</a>
      <button type="submit" class="btn btn-primary">
        <iconify-icon icon="lucide:save"></iconify-icon> Save Changes
      </button>
    </div>

  </form>

@endsection

@push('scripts')
<script>
  // Character counters
  document.getElementById('meta_title')?.addEventListener('input', function() {
    document.getElementById('meta_title_count').textContent = this.value.length;
  });
  document.getElementById('meta_description')?.addEventListener('input', function() {
    document.getElementById('meta_description_count').textContent = this.value.length;
  });
  document.getElementById('meta_keyword')?.addEventListener('input', function() {
    document.getElementById('meta_keyword_count').textContent = this.value.length;
  });

  // Initialize counters on page load
  window.addEventListener('load', function() {
    document.getElementById('meta_title_count').textContent = document.getElementById('meta_title')?.value.length || 0;
    document.getElementById('meta_description_count').textContent = document.getElementById('meta_description')?.value.length || 0;
    document.getElementById('meta_keyword_count').textContent = document.getElementById('meta_keyword')?.value.length || 0;
  });
</script>
@endpush
