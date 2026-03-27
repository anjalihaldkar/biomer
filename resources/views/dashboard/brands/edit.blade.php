{{-- resources/views/dashboard/brands/edit.blade.php --}}
@extends('layout.layout')

@section('title', 'Edit Brand')

@section('content')
<div class="container-fluid py-4">
  <div class="row justify-content-center">
    <div class="col-lg-6">

      <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('dashboard.brands.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
        <h2 class="mb-0 fw-bold">✏️ Edit Brand: {{ $brand->name }}</h2>
      </div>

      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <form action="{{ route('dashboard.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-3">
              <label class="form-label fw-semibold">Brand Name <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $brand->name) }}" required>
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Slug</label>
              <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                value="{{ old('slug', $brand->slug) }}">
              @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold">Brand Logo</label>
              @if($brand->logo)
                <div class="mb-2">
                  <img src="{{ Storage::url($brand->logo) }}" style="width:100px;height:100px;object-fit:contain;border:1px solid #dee2e6;border-radius:8px;padding:4px;">
                  <div class="text-muted small mt-1">Current logo. Upload a new one to replace.</div>
                </div>
              @endif
              <input type="file" name="logo" class="form-control" accept="image/*" onchange="previewLogo(this)">
              <img id="logoPreview" src="" alt="" style="display:none;width:100px;height:100px;object-fit:contain;margin-top:10px;border:1px solid #dee2e6;border-radius:8px;padding:4px;">
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-success btn-lg">💾 Update Brand</button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>
<script>
  function previewLogo(input) {
    const prev = document.getElementById('logoPreview');
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = e => { prev.src = e.target.result; prev.style.display = 'block'; };
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>
@endsection