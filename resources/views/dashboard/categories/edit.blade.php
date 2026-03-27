{{-- resources/views/dashboard/categories/edit.blade.php --}}
@extends('layout.layout')

@section('title', 'Edit Category')

@section('content')
<div class="container-fluid py-4">
  <div class="row justify-content-center">
    <div class="col-lg-6">

      <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('dashboard.categories.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
        <h2 class="mb-0 fw-bold">✏️ Edit: {{ $category->name }}</h2>
      </div>

      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <form action="{{ route('dashboard.categories.update', $category) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
              <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $category->name) }}" required>
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Slug</label>
              <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                value="{{ old('slug', $category->slug) }}">
              @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold">Parent Category</label>
              <select name="parent_id" class="form-select">
                <option value="">— None (Top Level) —</option>
                @foreach($parents as $parent)
                  <option value="{{ $parent->id }}"
                    {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                    {{ $parent->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-success btn-lg">💾 Update Category</button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection